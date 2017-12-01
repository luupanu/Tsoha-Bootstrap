<?php

  class BaseController{

    public static function get_user_logged_in(){
      if (isset($_SESSION['user'])){
        $id = $_SESSION['user'];
        $serviceuser = ServiceUser::find($id);
        return $serviceuser;
      }
      return null;
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
  }
