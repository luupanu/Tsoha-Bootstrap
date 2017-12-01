<?php

  class ServiceUserController extends BaseController{
  
    public static function handle_login() {
      $params = $_POST;

      $serviceuser = ServiceUser::authenticate($params['username'], $params['password']);

      if (!$serviceuser){
        View::make('suunnitelmat/login.html', array('error' => "Wrong username or password.",
          'username' => $params['username']));
      } else {
        $_SESSION['user'] = $serviceuser->id;

        Redirect::to('/library', array('message' => 'Welcome back ' . 
          $serviceuser->name . '!'));
      }
    }

    public static function login() {
      View::make('suunnitelmat/login.html');
    }

    public static function register() {
      View::make('suunnitelmat/register.html');
    }

    public static function store(){
      $params = $_POST;

      if ($params['password2'] !== $params['password']){
        return self::alert();
      } else {

      $serviceuser = new ServiceUser(array(
      'name' => $params['name'],
      'password' => $params['password']));

      $serviceuser->save();

      Redirect::to('/library');
      }
    }
    // replace this with js when there's time
    public static function alert() {
      $msg = "The passwords don't match!";
      echo '<script>alert("' . $msg . '")</script>';
    }
}