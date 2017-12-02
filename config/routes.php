<?php

function check_logged_in(){
  BaseController::check_logged_in();
}

$routes->get('/', 'check_logged_in', function() {
  SampleLibraryController::index();
});

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

$routes->post('/logout', function(){
  ServiceUserController::logout();
});

$routes->get('/profile', function() {
  ServiceUserController::profile();
});

$routes->post('/profile', function() {
  ServiceUserController::update();
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

$routes->get('/add', function() {
  SampleLibraryController::add();
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