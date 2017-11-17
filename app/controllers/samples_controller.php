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

      $sample = new Sample(array(
        'serviceuser_id' => '1',
        'filename' => $params['filename'],
        'name' => $params['name'],
        'duration' => $params['duration']));

      $sample->save();

      Redirect::to('/library');
    }
  }