<?php

  Class ProjectSample extends BaseModel{

    public $project_id, $sample_id;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function destroy($project_ids, $sample_id){
      $query = DB::connection()->prepare('
      DELETE FROM ProjectSample
      WHERE project_id NOT IN ('
        . implode(', ', $project_ids)
        . ')' .
      'AND sample_id = :sample_id');
      $query->execute(array('sample_id' => $sample_id));
    }

    public static function save($project_ids, $sample_id){
      $query = DB::connection()->prepare('
        INSERT INTO ProjectSample (sample_id, project_id)
        VALUES (:sample_id, '
          . implode('), (:sample_id, ', $project_ids)
          . ')' .
          'ON CONFLICT DO NOTHING');
      $query->execute(array('sample_id' => $sample_id));
    }
  }