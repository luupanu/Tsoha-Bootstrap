<?php

  class Project extends BaseModel{

  	public $id, $name;

  	public function __construct($attributes){
      parent::__construct($attributes);
  	}

  	public static function all(){
  	  $query = DB::connection()->prepare('SELECT * FROM Project');
  	  $query->execute();
  	  $rows = $query->fetchAll();
  	  $projects = [];

  	  foreach($rows as $row){
  	  	$projects[] = new Project(array(
  	  	  'id' => $row['id'],
  	  	  'name' => $row['name'] 
  	  	));
  	  }
      return $projects;
  	}

    public static function delete($project_ids, $sample_id){
      /*  first delete references to projects from this $sample_id
          that were NOT in the projects updated */
      $query_count = DB::connection()->prepare('
        DELETE
        FROM ProjectSample
        WHERE project_id NOT IN ('
          . implode(', ', $project_ids)
          . ')' .
        'AND sample_id = :sample_id');
      $query_count->execute(array('sample_id' => $sample_id));

      // then delete all projects that are left hanging with no references
      $query = DB::connection()->prepare('
        DELETE FROM Project
        WHERE id NOT IN
        (SELECT project_id FROM ProjectSample)
        OR length(name) < 1');
      $query->execute();
    }

    public static function update($project_names, $sample_id){
      // Insert ONLY new projects
      $query_project = DB::connection()->prepare('
        INSERT INTO Project (name)
        VALUES (\''
          . implode('\'), (\'', $project_names)
          . '\') ' .
        'ON CONFLICT (name) DO NOTHING');
      $query_project->execute();

      // Fetch all project ids - inserted AND those that conflicted
      $query_select = DB::connection()->prepare('
        SELECT id FROM Project
        WHERE name IN (\''
          . implode('\', \'', $project_names)
          . '\')');
      $query_select->execute();
      $project_ids = array_column($query_select->fetchAll(PDO::FETCH_ASSOC), 'id');

      // Insert ONLY new projectsamples
      $query_projectsample = DB::connection()->prepare('
        INSERT INTO ProjectSample (sample_id, project_id)
        VALUES (:sample_id, '
          . implode('), (:sample_id, ', $project_ids)
          . ')' .
          'ON CONFLICT DO NOTHING');
      $query_projectsample->execute(array(
        'sample_id' => $sample_id));

      // delete projects
      self::delete($project_ids, $sample_id);
    }
  }