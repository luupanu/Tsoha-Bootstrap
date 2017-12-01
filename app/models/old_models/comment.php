<?php

  class Comment extends BaseModel{

  	public $id, $sample_id, $comment;

  	public function __construct($attributes){
  	  parent::__construct($attributes);
  	}

  	public static function all(){
  	  $query = DB::connection()->prepare('SELECT * FROM Comment');
  	  $query->execute();
  	  $rows = $query->fetchAll();
  	  $comments = array();

  	  foreach ($rows as $row) {
  	  	$comments[] = new Comment(array(
          'id' => $row['id'],
          'sample_id' => $row['sample_id'],
          'comment' => $row['comment']
        ));
  	  }
      return $comments;
  	}

    public static function findAllCommentsBySampleId($id){
      $query = DB::connection()->prepare('SELECT * FROM Comment WHERE sample_id = :id');
      $query->execute(array('id' => $id));
      $rows = $query->fetchAll();
      $comments = array();

      foreach ($rows as $row) {
        $comments[] = new Comment(array(
          'id' => $row['id'],
          'sample_id' => $row['sample_id'],
          'comment' => $row['comment']
        ));
      }
      return $comments;
    }
  }