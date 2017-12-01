<?php

class SampleLibraryController extends BaseController{
	public static function index(){
		$samples = Sample::all(self::get_user_logged_in()->id);
    View::make('suunnitelmat/samplelibrary.html', array('samples' => $samples));
	}

  public static function addSamples() {
      View::make('suunnitelmat/addsamples.html');
    }

  public static function destroy($id){
    $sample = Sample::find($id);
    $sample->destroy();
    Tag::destroy($sample->tags, $id);
    Project::destroy($sample->projects, $id);

    Redirect::to('/library', array('message' => 'Sample removed successfully!'));
  }

  public static function store(){
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
      View::make('suunnitelmat/addsamples.html', array('errors' => $errors));
    } else {
      $sample->save();
      Redirect::to('/library');
    }
  }

  public static function update($id){
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
      $samples = Sample::all(self::get_user_logged_in()->id);
      View::make('suunnitelmat/samplelibrary.html', array('samples' => $samples, 'errors' => $errors));
    } else {
      $sample->update();
      Redirect::to('/library', array('message' => 'Sample updated successfully!'));
    }
  }
}