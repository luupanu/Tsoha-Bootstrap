<?php

  class SampleTag extends BaseModel{

    public $sample_id, $tag_name;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function findAllTagsBySampleId($id){
      $query = DB::connection()->prepare('SELECT * FROM Tag, SampleTag WHERE SampleTag.sample_id = :id AND SampleTag.tag_id = Tag.id');
      $query->execute(array('id' => $id));
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