<?php

  class SampleLibraryController extends BaseController{
  	public static function index(){
  		$samples = Sample::all();
  		/*$tags = Tag::all();
      $comments = Comment::all();
      $projects = Project::all();*/
      View::make('suunnitelmat/samplelibrary.html', array('samples' => $samples));
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
  }