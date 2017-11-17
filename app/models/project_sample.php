<?php

  Class ProjectSample extends BaseModel{

    public $project_id, $sample_id;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function findAllProjectsBySampleId($id){
      $query = DB::connection()->prepare('SELECT * FROM Project, ProjectSample WHERE ProjectSample.sample_id = :id AND ProjectSample.project_id = Project.id');
      $query->execute(array('id' => $id));
      $rows = $query->fetchAll();
      $projects = array();

      foreach ($projects as $project){
        $projects[] = new Project(array(
          'id' => $rows['id'],
          'name' => $rows['name']
        ));
      }
      return $projects;
    }
  }