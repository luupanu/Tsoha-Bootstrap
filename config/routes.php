<?php

  $routes->get('/', function() {
    HelloWorldController::login();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/login', function() {
    HelloWorldController::login();
  });

  $routes->get('/register', function() {
    HelloWorldController::register();
  });

  $routes->post('/register', function() {
    ServiceUserController::store();
  });

  $routes->get('/library', function() {
  	SampleLibraryController::index();
  });

  $routes->get('/addsamples', function() {
    HelloWorldController::addSamples();
  });

  $routes->post('/add', function() {
    SampleLibraryController::store();
  });

