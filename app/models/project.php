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
  	  $projects = array();

  	  foreach($rows as $row){
  	  	$projects[] = new Project(array(
  	  	  'id' => $row['id'],
  	  	  'name' => $row['name'] 
  	  	));
  	  }
      return $projects;
  	}
  }