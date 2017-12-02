<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      $sample1 = Sample::find(1);
      $sample2 = Sample::find(2);
      $samples = Sample::all();
      $tags = Tag::all();
      $projects = Project::all();
      $tagsById = SampleTag::findAllTagsBySampleId('2');
      $projectsById = ProjectSample::findAllProjectsBySampleId('1');
      

      $user = ServiceUser::authenticate('MikkoMies', 'zalazana');
      $fakeuser = ServiceUser::authenticate('MikkoMies', 'password');

      Kint::dump($samples);
      Kint::dump($sample1);
      Kint::dump($sample2);
      Kint::dump($user);
      Kint::dump($fakeuser);
      Kint::dump($tags);
      Kint::dump($projects);
      Kint::dump($tagsById);
      Kint::dump($projectsById);
      //View::make('helloworld.html');
    }
    

    public static function sampleLibrary() {
      View::make('/library.html');
    }
  }
