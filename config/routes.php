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

$routes->get('/add', function() {
  SampleLibraryController::add();
});

$routes->get('/admin', function() {
  ServiceUserController::admin();
});

$routes->get('/library', function() {
  SampleLibraryController::index();
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

$routes->post('/profile/destroy', function() {
  ServiceUserController::destroy();
});

$routes->get('/register', function() {
  ServiceUserController::register();
});

$routes->post('/register', function() {
  ServiceUserController::store();
});

$routes->post('/sample/add', function() {
  SampleLibraryController::store();
});

$routes->post('/sample/add/json', function() {
  SampleLibraryController::storeJsonFile();
});

$routes->post('/sample/:id/edit', function($id) {
  SampleLibraryController::update($id);
});

$routes->post('/sample/:id/destroy', function($id) {
  SampleLibraryController::destroy($id);
});

$routes->post('/user/:id/destroy', function($id) {
  ServiceUserController::destroyById($id);
});