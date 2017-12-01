<?php

  class SampleTag extends BaseModel{

    public $sample_id, $tag_name;

    public function __construct($attributes){
      parent::__construct($attributes);
    }

    public static function destroy($tag_ids, $sample_id){
      $query = DB::connection()->prepare('
        DELETE FROM SampleTag
        WHERE tag_id NOT IN ('
          . implode(', ', $tag_ids)
          . ')' .
        'AND sample_id = :sample_id');
      $query->execute(array('sample_id' => $sample_id));
    }

    public static function save($tag_ids, $sample_id){
      $query = DB::connection()->prepare('
        INSERT INTO SampleTag (sample_id, tag_id)
        VALUES (:sample_id, '
          . implode('), (:sample_id, ', $tag_ids)
          . ')' .
          'ON CONFLICT DO NOTHING');
      $query->execute(array('sample_id' => $sample_id));
    }
  }