<?php

  class ServiceUser extends BaseModel{

  	public $id, $name, $password, $superuser;

  	public function __construct($attributes){
  		parent::__construct($attributes);
  	}

  	public static function authenticate($name, $password){
  		$query = DB::connection()->prepare('SELECT * FROM ServiceUser WHERE name = :name AND password = :password LIMIT 1');
  		$query->execute(array('name' => $name, 'password' => $password));
  		$row = $query->fetch();

  		if($row){
  			$serviceuser = new ServiceUser(array(
  				'id' => $row['id'],
  				'name' => $row['name'],
  				'password' => $row['name'],
  				'superuser' => $row['superuser']));

  			return $serviceuser;
  		}
  		return null;
  	}

    public static function find($id){
      $query = DB::connection()->prepare('SELECT * FROM ServiceUser WHERE id = :id');
      $query->execute(array('id' -> $id));
      $row = $query->fetch();
      if ($row){
        $serviceuser = new ServiceUser(array(
          $id -> $row['id'],
          $name -> $row['name'],
          $password -> $row['password'],
          $superuser -> $row['superuser']));
        return $serviceuser;
      }
      return null;
    }

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO ServiceUser (name, password) VALUES (:name, :password) RETURNING id');
      $query->execute(array(
        'name' => $this->name,
        'password' => $this->password));
      $row = $query->fetch();
      Kint::trace();
      Kint::dump($row);
      $this->id = $row['id'];
    }
  }