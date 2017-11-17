<?php
  
  class Tag extends BaseModel{

  	public $name;

  	public function __construct($attributes){
  	  parent::__construct($attributes);
  	}

  	public static function all(){
  	  $query = DB::connection()->prepare('SELECT * FROM Tag');
  	  $query->execute();
      $rows = $query->fetchAll();
      $tags = array();

      foreach($rows as $row){
      	$tags[] = new Tag(array(
      	  'name' => $row['name']
      	));
      }
      return $tags;
  	}
  }