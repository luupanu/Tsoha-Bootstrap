<?php

class SampleLibraryController extends BaseController{
	public static function index(){
    self::check_logged_in();
    $params = $_GET;
    $options = array('serviceuser_id' => self::get_user_logged_in()->id);
    if (isset($params['filter'])){
      $options['filter'] = $params['filter'];
    }

    $samples = Sample::all($options);

    $view_array = array('samples' => $samples);
    if (isset($params['filter'])) {
      $view_array['filter'] = $params['filter'];
    }
    View::make('library.html', $view_array);
	}

  public static function add(){
    self::check_logged_in();
    View::make('add.html');
  }
  
  public static function destroy($id){
    self::check_logged_in();
    $sample = Sample::find($id);
    $sample->destroy();
    Tag::destroy($sample->tags, $id);
    Project::destroy($sample->projects, $id);

    Redirect::to('/library', array('messages' => 'Sample removed successfully!'));
  }

  public static function store(){
    self::check_logged_in();
    $params = $_POST;
    $serviceuser_id = self::get_user_logged_in()->id;

    $sample = new Sample(array(
      'serviceuser_id' => $serviceuser_id,
      'filename' => $params['filename'],
      'name' => $params['name'],
      'duration' => $params['duration']));

    $sample->validate();

    $errors = $sample->errors();

    if(count($errors) > 0){
      View::make('add.html', array('errors' => $errors));
    } else {
      $sample->save();
      Redirect::to('/library');
    }
  }

  public static function storeJsonFile(){
    self::check_logged_in();

    $params = $_POST;
    $file = file_get_contents($params['file']);
    $json_array = json_decode($file);
    $serviceuser_id = self::get_user_logged_in()->id;
    $samples = [];
    $errors = [];

    foreach ($json_array as $object){
      preg_match('/(.*\/)(?=(.*)).*/', $object->filename, $matches);
      $sample = new Sample(array(
        'serviceuser_id' => $serviceuser_id,
        'filename' => $matches[2],
        'duration' => $object->duration
      ));
      $samples[] = $sample;
      $sample->validate();
      $errors = array_merge($errors, $sample->errors());
    }

    if (count($errors) > 0){
      View::make('add.html', array('errors' => $errors));
    } else {
      foreach ($samples as $sample){
        $sample->save();
      }
      Redirect::to('/library');
    }
  }

  public static function update($id){
    self::check_logged_in();
    $params = $_POST;
    $keys = array_keys($params);
    $sample = Sample::find($id);

    foreach($keys as $key){
      if($key === 'tags' || $key === 'projects'){
        $params[$key] = explode(' ', $params[$key]);
      }
      if(property_exists($sample, $key)) {
        $sample->$key = $params[$key];
      }
    }

    $sample->validate();

    $errors = $sample->errors();

    if(count($errors) > 0){
      $samples = Sample::all(array('serviceuser_id' => self::get_user_logged_in()->id));
      View::make('library.html', array('samples' => $samples, 'errors' => $errors));
    } else {
      $sample->update();
      Redirect::to('/library', array('message' => 'Sample updated successfully!'));
    }
  }
}