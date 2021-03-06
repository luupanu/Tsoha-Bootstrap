<?php

class Sample extends BaseModel{

	public $id, $serviceuser_id, $filename, $name, $duration, $comment, $tags, $projects;

	public function __construct($attributes){
		parent::__construct($attributes);
    if (!isset($tags)) {
      $this->tags = [];
    }
    if (!isset($projects)) {
      $this->projects = [];
    }
    $this->validators = ['validate'];
	}

	public static function all($options){
    $query_string = '
      SELECT Sample.id, Sample.serviceuser_id, Sample.filename, Sample.name, Sample.duration, Sample.comment, Tag.name AS tag_name, Project.name AS project_name
      FROM Sample
      LEFT JOIN SampleTag ON SampleTag.sample_id = Sample.id
      LEFT JOIN Tag ON SampleTag.tag_id = Tag.id
      LEFT JOIN ProjectSample ON ProjectSample.sample_id = Sample.id
      LEFT JOIN Project ON ProjectSample.project_id = Project.id
      WHERE Sample.serviceuser_id = :serviceuser_id';

    // filter by tags
    if (isset($options['filter']) && !empty($options['filter'])){
      $filters = preg_replace('/([^a-z0-9_åäö\s])/i', '', $options['filter']);
      $filters = preg_replace('/\s{2,}/', ' ', $filters);
      $filters = explode(' ', $filters);
      foreach ($filters as $filter){
        $query_string .= '
        AND Sample.id IN
        (SELECT Sample.id FROM Sample
        LEFT JOIN SampleTag ON SampleTag.sample_id = Sample.id
        LEFT JOIN Tag ON SampleTag.tag_id = Tag.id
        WHERE Tag.name = \'' . array_pop($filters) . '\')';
      }
    }
    $query_string .= '
      ORDER BY Sample.filename ASC, Tag.name ASC, Project.name ASC';

		$query = DB::connection()->prepare($query_string);
    $options = array('serviceuser_id' => $options['serviceuser_id']);
		$query->execute($options);
		$rows = $query->fetchAll();

		return self::createSamplesFromRows($rows);
	}

  public static function countSamples($serviceuser_id){
    $query = DB::connection()->prepare('
      SELECT COUNT(*)
      FROM Sample
      WHERE serviceuser_id = :serviceuser_id');
    $query->execute(array('serviceuser_id' => $serviceuser_id));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return array_pop($row);
  }

  public function destroy(){
    $query = DB::connection()->prepare('
      DELETE FROM Sample
      WHERE Sample.id = :id');
    $query->execute(array('id' => $this->id));
  }

	public static function find($id){
		$query = DB::connection()->prepare('
      SELECT Sample.id, Sample.serviceuser_id, Sample.filename, Sample.name, Sample.duration, Sample.comment, Tag.name AS tag_name, Project.name AS project_name
      FROM Sample
      LEFT JOIN SampleTag ON SampleTag.sample_id = Sample.id
      LEFT JOIN Tag ON SampleTag.tag_id = Tag.id
      LEFT JOIN ProjectSample ON ProjectSample.sample_id = Sample.id
      LEFT JOIN Project ON ProjectSample.project_id = Project.id
      WHERE Sample.id = :id');
		$query->execute(array('id' => $id));
		$rows = $query->fetchAll();

    $sample = self::createSamplesFromRows($rows);
    return array_pop($sample);
	}

  public function save(){
    $query = DB::connection()->prepare('
      INSERT INTO Sample (serviceuser_id, filename, name, duration, comment)
      VALUES (:serviceuser_id, :filename, :name, :duration, :comment) RETURNING id');
    $query->execute(array(
      'serviceuser_id' => $this->serviceuser_id,
      'filename' => $this->filename,
      'name' => $this->name,
      'duration' => $this->duration,
      'comment' => $this->comment));
    $row = $query->fetch();
    $this->id = $row['id'];
  }

  public static function sumDuration($serviceuser_id){
    $query = DB::connection()->prepare('
      SELECT SUM(duration)
      FROM Sample
      WHERE serviceuser_id = :serviceuser_id;');
    $query->execute(array('serviceuser_id' => $serviceuser_id));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return array_pop($row);
  }

  public function update(){
    $query = DB::connection()->prepare('
      UPDATE Sample
      SET serviceuser_id = :serviceuser_id, filename = :filename, name = :name, duration = :duration, comment = :comment
      WHERE id = :id');
    $query->execute(array(
      'serviceuser_id' => $this->serviceuser_id,
      'filename' => $this->filename,
      'name' => $this->name,
      'duration' => $this->duration,
      'comment' => $this->comment,
      'id' => $this->id));
    /*  because our view has Tags and Projects tied to Samples,
        need to update them as well */
    Tag::update($this->tags, $this->id);
    Project::update($this->projects, $this->id);
  }

  public function validate(){
    $v = new Valitron\Validator(get_object_vars($this));
    $v->rule('required', ['serviceuser_id', 'filename', 'duration']);
    $v->rule('integer', 'serviceuser_id');
    $v->rule('numeric', 'duration');
    $v->rule('array', ['tags', 'projects']);
    $v->rule('lengthBetween', ['name', 'tags.*', 'projects.*'], 0, 50);
    $v->rule('lengthBetween', 'filename', 1, 260);
    $v->rule('lengthBetween', 'comment', 0, 140);
    $v->rule('min', 'duration', '0');
    $v->rule('max', 'duration', '9999');
    $v->rule('regex', 'tags.*', '/^([a-z0-9_åäö])*$/i')->message('
      {field} can only contain alphanumeric characters, dashes and underscores');
    $v->labels(array(
      'filename' => $this->filename . ': Filename',
      'name' => $this->filename . ': Name',
      'duration' => $this->filename . ': Duration',
      'comment' => $this->filename . ': Comment',
      'tags.*' => $this->filename . ': Tags',
      'projects.*' => $this->filename . ': Projects'
    ));
    $v->validate();
    return $v->errors();
  }

  private static function createSamplesFromRows($rows) {
    $samples = [];
    $current_row_id = null; // keeps track of Sample.id
    $sample = null;

    foreach($rows as $row){
      // if this row has a new Sample.id, create a new instance of Sample
      if ($row['id'] != $current_row_id){
        $sample = new Sample(array(
          'id' => $row['id'],
          'serviceuser_id' => $row['serviceuser_id'],
          'filename' => $row['filename'],
          'name' => $row['name'],
          'duration' => $row['duration'],
          'comment' => $row['comment']));
        $samples[] = $sample;
        $current_row_id = $row['id'];
      }
      $tag_name = $row['tag_name'];
      $project_name = $row['project_name'];
      // always add a new tag to this sample's $tags if it isn't there already
      if (!is_null($tag_name) && !in_array($tag_name, $sample->tags)){
        $sample->tags[] = $tag_name;
      }
      // always add a new project to this sample's $projects if it isn't there already
      if (!is_null($project_name) && !in_array($project_name, $sample->projects)){
        $sample->projects[] = $project_name;
      }
    }
    return $samples;
  }
}