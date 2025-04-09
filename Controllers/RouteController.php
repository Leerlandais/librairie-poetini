<?php
namespace Controllers;
use    model\Manager\RouteManager;


$router = new RouteManager($twig,$db);

// Register routes
$router->registerRoute('home', HomeController::class, 'index');

// Handle request
$route = $_GET['route'] ?? 'home'; // use the usual method to set the default page
$router->handleRequest($route);