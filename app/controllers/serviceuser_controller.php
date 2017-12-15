<?php

class ServiceUserController extends BaseController{

  public static function destroy(){
    self::check_logged_in();
    $serviceuser = self::get_user_logged_in();
    $serviceuser->destroy();
    $_SESSION['user'] = null;
    Redirect::to('/', array('message' => 'Account removed successfully!'));
  }

  public static function handle_login(){
    self::check_logged_out();
    $params = $_POST;

    $serviceuser = ServiceUser::authenticate($params['username'], $params['password']);

    if (!$serviceuser){
      View::make('/login.html', array('error' => 'Wrong username or password.',
        'username' => $params['username']));
    } else {
      $_SESSION['user'] = $serviceuser->id;

      $messages = ['Welcome back ' . $serviceuser->name . '!',
        'Click on the table cells to edit!'];
      Redirect::to('/library', array('messages' => $messages));
    }
  }

  public static function login(){
    self::check_logged_out();
    View::make('/login.html');
  }

  public static function logout(){
    self::check_logged_in();
    $_SESSION['user'] = null;
    Redirect::to('/login', array());
  }

  public static function profile(){
    self::check_logged_in();
    $id = self::get_user_logged_in()->id;
    $username = self::get_user_logged_in()->name;
    $samples_total = Sample::countSamples($id);
    $duration_total = self::buildTimeString(Sample::sumDuration($id));

    View::make('/profile.html', array(
      'username' => $username,
      'samples_total' => $samples_total,
      'duration_total' => $duration_total));
  }

  public static function register(){
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

    if (!is_null(ServiceUser::findByName($params['username']))){
      $errors[] = 'This username already exists.';
    }

    if ($params['password2'] !== $params['password']){
      $errors[] = 'The passwords don\'t match';
    }

    if (count($errors) > 0){
      View::make('/register.html', array('errors' => $errors,
        'username' => $params['username']));
    } else {
      $serviceuser->save();
      $_SESSION['user'] = $serviceuser->id;
      $messages = ['Welcome back ' . $serviceuser->name . '!',
        'Click on the table cells to edit!'];
      Redirect::to('/library', array('messages' => $messages));
    }
  }

  public static function update(){
    self::check_logged_in();
    $params = $_POST;
    $id = self::get_user_logged_in()->id;
    
    $serviceuser_old = ServiceUser::findById($id);

    $serviceuser = new ServiceUser(array(
      'id' => $id,
      'name' => $serviceuser_old->name,
      'password' => $params['password'],
      'superuser' => $serviceuser_old->superuser));

    $serviceuser->validate();
    $errors = $serviceuser->errors();

    if (!password_verify($params['password_old'], $serviceuser_old->password)) {
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

  private static function buildTimeString($seconds){
    $seconds = (int) $seconds;
    $hours = intdiv($seconds, 3600);
    $minutes = intdiv(($seconds - $hours * 3600), 60);
    $seconds = $seconds - $hours * 3600 - $minutes * 60;
    return $hours . ' hours ' . $minutes. ' minutes and ' . $seconds . ' seconds';
  }
}