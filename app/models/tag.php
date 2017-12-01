<?php
  
class Tag extends BaseModel{

	public $id, $name;

	public function __construct($attributes){
	  parent::__construct($attributes);
	}

	public static function all(){
	  $query = DB::connection()->prepare('SELECT * FROM Tag');
	  $query->execute();
    $rows = $query->fetchAll();
    $tags = [];

    foreach($rows as $row){
    	$tags[] = new Tag(array(
        'id' => $row['id'],
    	  'name' => $row['name']
    	));
    }
    return $tags;
	}

  public static function delete($tag_ids, $sample_id){
    /*  first delete references to tags from this $sample_id
        that were NOT in the tags updated */
    $query_count = DB::connection()->prepare('
      DELETE
      FROM SampleTag
      WHERE tag_id NOT IN ('
        . implode(', ', $tag_ids)
        . ')' .
      'AND sample_id = :sample_id');
    $query_count->execute(array('sample_id' => $sample_id));

    // then delete all tags that are left hanging with no references
    $query = DB::connection()->prepare('
      DELETE FROM Tag
      WHERE id NOT IN
      (SELECT tag_id FROM SampleTag)
      OR length(name) < 1');
    $query->execute();
  }

  public static function update($tag_names, $sample_id){
    // Insert ONLY new tags
    $query_tag = DB::connection()->prepare('
      INSERT INTO Tag (name)
      VALUES (\''
        . implode('\'), (\'', $tag_names)
        . '\') ' .
      'ON CONFLICT (name) DO NOTHING');
    $query_tag->execute();

    // Fetch all tag ids - inserted AND those that conflicted
    $query_select = DB::connection()->prepare('
      SELECT id FROM Tag
      WHERE name IN (\''
        . implode('\', \'', $tag_names)
        . '\')');
    $query_select->execute();
    $tag_ids = array_column($query_select->fetchAll(PDO::FETCH_ASSOC), 'id');

    // Insert ONLY new sampletags 
    $query_sampletag = DB::connection()->prepare('
      INSERT INTO SampleTag (sample_id, tag_id)
      VALUES (:sample_id, '
        . implode('), (:sample_id, ', $tag_ids)
        . ')' .
        'ON CONFLICT DO NOTHING');
    $query_sampletag->execute(array(
      'sample_id' => $sample_id));

    // delete tags
    self::delete($tag_ids, $sample_id);
  }
}