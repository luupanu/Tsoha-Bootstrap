<?php

  class ServiceUserController extends BaseController{
  
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

    public static function alert() {
      $msg = "The passwords don't match!";
      echo '<script>alert("' . $msg . '")</script>';
    }
}