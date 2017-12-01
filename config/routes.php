<?php

$routes->get('/', function() {
  ServiceUserController::login();
});

$routes->get('/hiekkalaatikko', function() {
  HelloWorldController::sandbox();
});

$routes->get('/login', function() {
  ServiceUserController::login();
});

$routes->post('/login', function() {
  ServiceUserController::handle_login();
});

$routes->get('/register', function() {
  ServiceUserController::register();
});

$routes->post('/register', function() {
  ServiceUserController::store();
});

$routes->get('/library', function() {
	SampleLibraryController::index();
});

$routes->get('/addsamples', function() {
  SampleLibraryController::addSamples();
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

