<?php

  class Sample extends BaseModel{

  	public $id, $serviceuser_id, $filename, $name, $duration;

  	public function __construct($attributes){
  		parent::__construct($attributes);
  	}

  	public static function all(){
  		$query = DB::connection()->prepare('SELECT * FROM Sample');
  		$query->execute();
  		$rows = $query->fetchAll();
  		$samples = array();

  		foreach($rows as $row){
  			$samples[] = new Sample(array(
  				'id' => $row['id'],
  				'serviceuser_id' => $row['serviceuser_id'],
  				'filename' => $row['filename'],
  				'name' => $row['name'],
  				'duration' => $row['duration']
  			));
  		}
  		return $samples;
  	}

  	public static function find($id){
  		$query = DB::connection()->prepare('SELECT * FROM Sample WHERE id = :id LIMIT 1');
  		$query->execute(array('id' => $id));
  		$row = $query->fetch();

  		if($row){
  			$sample = new Sample(array(
  				$id = $row['id'],
  				$serviceuser_id = $row['serviceuser_id'],
  				$filename = $row['filename'],
  				$name = $row['name'],
  				$duration = $row['duration']
  			));
  			return $sample;
  		}
  		return null;
  	}

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO Sample (serviceuser_id, filename, name, duration) VALUES (:serviceuser_id, :filename, :name, :duration) RETURNING id');
      $query->execute(array(
        'serviceuser_id' => $this->serviceuser_id,
        'filename' => $this->filename,
        'name' => $this->name,
        'duration' => $this->duration
      ));
      $row = $query->fetch();
      Kint::trace();
      Kint::dump($row);
      $this->id = $row['id'];
    }
  }