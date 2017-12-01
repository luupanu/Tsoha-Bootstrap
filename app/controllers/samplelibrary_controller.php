<?php

class SampleLibraryController extends BaseController{
	public static function index(){
		$samples = Sample::all();
    View::make('suunnitelmat/samplelibrary.html', array('samples' => $samples));
	}

  public static function destroy($id){
    $sample = new Sample(['id' => $id]);
    $sample->destroy();
  }

  public static function store(){
    $params = $_POST;

    $duration = filter_var($params['duration'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

    if ($duration > 9999 || $duration < 0) return;

    $sample = new Sample(array(
      'serviceuser_id' => '1',
      'filename' => $params['filename'],
      'name' => $params['name'],
      'duration' => $duration));

    $sample->save();

    Redirect::to('/library');
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
      $samples = Sample::all();
      View::make('suunnitelmat/samplelibrary.html', array('samples' => $samples, 'errors' => $errors));
    } else {
      $sample->update();
      Redirect::to('/library', array('message' => 'Update successful!'));
    }
  }
}