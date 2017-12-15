<?php

  class BaseController{

    public static function check_is_admin(){
      $serviceuser = self::get_user_logged_in();
      if (is_null($serviceuser)) {
        Redirect::to('/login');
      } else if (!$serviceuser->superuser){
        Redirect::to('/');
      }
    }

    public static function check_logged_in(){
      if (!isset($_SESSION['user'])){
        Redirect::to('/login');
      }
    }

    public static function check_logged_out(){
      if (isset($_SESSION['user'])){
        Redirect::to('/');
      }
    }

    public static function get_is_admin(){
      $serviceuser = self::get_user_logged_in();
      if (is_null($serviceuser)) return false;
      return $serviceuser->superuser;
    }

    public static function get_user_logged_in(){
      if (isset($_SESSION['user'])){
        $id = $_SESSION['user'];
        return ServiceUser::findById($id);
      }
      return null;
    }
  }
