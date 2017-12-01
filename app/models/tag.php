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

  public static function destroy($tag_names, $sample_id){
    $tag_ids = self::findIds($tag_names);
    if (empty($tag_ids)) return;
    /*  first delete references to tags from this $sample_id
        that were NOT in the tags updated */
    SampleTag::destroy($tag_ids, $sample_id);

    // then delete all tags that are left hanging with no references
    $query = DB::connection()->prepare('
      DELETE FROM Tag
      WHERE id NOT IN
      (SELECT tag_id FROM SampleTag)
      OR length(name) < 1');
    $query->execute();
  }

  public static function findIds($tag_names){
    $query = DB::connection()->prepare('
      SELECT id FROM Tag
      WHERE name IN (\''
        . implode('\', \'', $tag_names)
        . '\')');
    $query->execute();
    return array_column($query->fetchAll(PDO::FETCH_ASSOC), 'id');
  }

  public static function save($tag_names){
    $query = DB::connection()->prepare('
      INSERT INTO Tag (name)
      VALUES (\''
        . implode('\'), (\'', $tag_names)
        . '\') ' .
      'ON CONFLICT (name) DO NOTHING');
    $query->execute();
  }

  public static function update($tag_names, $sample_id){
    // Insert ONLY new tags
    self::save($tag_names);
    // Fetch all tag ids - inserted AND those that conflicted
    $tag_ids = self::findIds($tag_names);

    // Insert ONLY new sampletags 
    SampleTag::save($tag_ids, $sample_id);

    // delete tags
    self::destroy($tag_names, $sample_id);
  }
}