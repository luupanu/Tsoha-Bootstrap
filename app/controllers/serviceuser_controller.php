<?php

class ServiceUserController extends BaseController{

  public static function handle_login() {
    self::check_logged_out();
    $params = $_POST;

    $serviceuser = ServiceUser::authenticate($params['username'], $params['password']);

    if (!$serviceuser){
      View::make('/login.html', array('error' => 'Wrong username or password.',
        'username' => $params['username']));
    } else {
      $_SESSION['user'] = $serviceuser->id;

      Redirect::to('/library', array('message' => 'Welcome back ' . 
        $serviceuser->name . '!'));
    }
  }

  public static function login() {
    self::check_logged_out();
    View::make('/login.html');
  }

  public static function logout() {
    self::check_logged_in();
    $_SESSION['user'] = null;
    Redirect::to('/login', array());
  }

  public static function profile() {
    self::check_logged_in();
    View::make('/profile.html');
  }

  public static function register() {
    self::check_logged_out();
    View::make('/register.html');
  }

  public static function store(){
    self::check_logged_out();
    $params = $_POST;

    $serviceuser = new ServiceUser(array(
      'name' => $params['username'],
      'password' => $params['password']));

    $serviceuser->validate();
    $errors = $serviceuser->errors();

    if ($params['password2'] !== $params['password']){
      $errors[] = 'The passwords don\'t match';
    }

    if (count($errors) > 0){
      View::make('/register.html', array('errors' => $errors,
        'username' => $params['username']));
    } else {
      $serviceuser->save();
      Redirect::to('/library');
    }
  }

  public static function update(){
    self::check_logged_in();
    $params = $_POST;
    $id = self::get_user_logged_in()->id;
    
    $serviceuser_old = ServiceUser::find($id);

    $serviceuser = new ServiceUser(array(
      'id' => $id,
      'name' => $serviceuser_old->name,
      'password' => $params['password'],
      'superuser' => $serviceuser_old->superuser));

    $serviceuser->validate();
    $errors = $serviceuser->errors();

    if ($params['password_old'] !== $serviceuser_old->password) {
      $errors[] = 'The old password was incorrect';
    }

    if ($params['password2'] !== $params['password']){
      $errors[] = 'The new passwords don\'t match';
    }

    if (count($errors) > 0){
      View::make('/profile.html', array('errors' => $errors));
    } else {
      $serviceuser->update();
      Redirect::to('/profile', array('message' => 'Password changed successfully!'));
    }
  }
}