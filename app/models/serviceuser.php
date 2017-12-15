<?php

  class ServiceUser extends BaseModel{

  	public $id, $name, $password, $superuser;

  	public function __construct($attributes){
  		parent::__construct($attributes);
      $this->validators = ['validate'];
  	}

    public static function all(){
      $query = DB::connection()->prepare('
        SELECT * FROM ServiceUser');
      $query->execute();
      $rows = $query->fetchAll();
      $serviceusers = [];

      foreach ($rows as $row){
        $serviceusers[] = new ServiceUser(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'superuser' => $row['superuser']));
      }
      return $serviceusers;
    }

  	public static function authenticate($name, $password){
      $name = strtolower($name);  // case insensitive select
  		$query = DB::connection()->prepare('
        SELECT * FROM ServiceUser
        WHERE LOWER(name) = :name
        LIMIT 1');
  		$query->execute(array('name' => $name));
  		$row = $query->fetch();

  		if($row && password_verify($password, $row['password'])){
  			$serviceuser = new ServiceUser(array(
  				'id' => $row['id'],
  				'name' => $row['name'],
  				'password' => $row['password'],
  				'superuser' => $row['superuser']));

  			return $serviceuser;
  		}
  		return null;
  	}

    public function destroy(){
      $query = DB::connection()->prepare('
      DELETE FROM ServiceUser
      WHERE ServiceUser.id = :id');
      $query->execute(array('id' => $this->id));
    }

    public static function findById($id){
      $query = DB::connection()->prepare(
        'SELECT * FROM ServiceUser
        WHERE id = :id
        LIMIT 1');
      $query->execute(array('id' => $id));
      $row = $query->fetch();
      if ($row){
        $serviceuser = new ServiceUser(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'password' => $row['password'],
          'superuser' => $row['superuser']));
        return $serviceuser;
      }
      return null;
    }

    public static function findByName($name){
      $name = strtolower($name);  // case insensitive select
      $query = DB::connection()->prepare(
        'SELECT * FROM ServiceUser
        WHERE LOWER(name) = :name');
      $query->execute(array('name' => $name));
      $row = $query->fetch();
      if ($row){
        $serviceuser = new ServiceUser(array(
          'id' => $row['id'],
          'name' => $row['name'],
          'password' => $row['password'],
          'superuser' => $row['superuser']));
        return $serviceuser;
      }
      return null;
    }

    public function save(){
      $this->password = password_hash($this->password, PASSWORD_DEFAULT);
      $query = DB::connection()->prepare('
        INSERT INTO ServiceUser (name, password)
        VALUES (:name, :password)
        RETURNING id');
      $query->execute(array(
        'name' => $this->name,
        'password' => $this->password));
      $row = $query->fetch();
      $this->id = $row['id'];
    }

    public function update(){
      $this->password = password_hash($this->password, PASSWORD_DEFAULT);
      $query = DB::connection()->prepare('
        UPDATE ServiceUser
        SET name = :name, password = :password
        WHERE id = :id');
      $query->execute(array(
        'name' => $this->name,
        'password' => $this->password));
    }

    public function validate(){
      $v = new Valitron\Validator(get_object_vars($this));
      $v->rule('required', ['name', 'password']);
      $v->rule('lengthBetween', 'name', 2, 16);
      $v->rule('lengthBetween', 'password', 6, 50);
      $v->validate();
      return $v->errors();
    }
  }