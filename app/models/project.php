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

  public static function destroy($project_names, $sample_id){
    $project_ids = self::findIds($project_names);
    if (empty($project_ids)) return;
    /*  first delete references to projects from this $sample_id
        that were NOT in the projects updated */
    ProjectSample::destroy($project_ids, $sample_id);

    // then delete all projects that are left hanging with no references
    $query = DB::connection()->prepare('
      DELETE FROM project
      WHERE id NOT IN
      (SELECT project_id FROM ProjectSample)
      OR name = \'\'');
    $query->execute();
  }

  public static function findIds($project_names){
    $query = DB::connection()->prepare('
      SELECT id FROM Project
      WHERE name IN (\''
        . implode('\', \'', $project_names)
        . '\')');
    $query->execute();
    return array_column($query->fetchAll(PDO::FETCH_ASSOC), 'id');
  }

  public static function save($project_names){
    $query = DB::connection()->prepare('
      INSERT INTO Project (name)
      VALUES (\''
        . implode('\'), (\'', $project_names)
        . '\') ' .
      'ON CONFLICT (name) DO NOTHING');
    $query->execute();
  }

  public static function update($project_names, $sample_id){
    // Insert ONLY new projects
    self::save($project_names);
    // Fetch all project ids - inserted AND those that conflicted
    $project_ids = self::findIds($project_names);

    // Insert ONLY new sampleprojects 
    ProjectSample::save($project_ids, $sample_id);

    // delete projects
    self::destroy($project_names, $sample_id);
  }
}