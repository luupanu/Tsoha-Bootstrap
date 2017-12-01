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

$routes->post('/sample/add', function() {
  SampleLibraryController::store();
});

$routes->post('/sample/:id/edit', function($id) {
  SampleLibraryController::update($id);
});

$routes->post('/sample/:id/destroy', function($id) {
  SampleLibraryController::destroy($id);
});

