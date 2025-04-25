<?php
namespace Controllers;
use    model\Manager\RouteManager;


$router = new RouteManager($twig,$db);

// Register routes
$router->registerRoute('home', HomeController::class, 'index');
$router->registerRoute("track-click", HomeController::class, 'trackClick');
$router->registerRoute("404", HomeController::class, 'notFound');
$router->registerRoute("displayAll", HomeController::class, 'showLogs');
$router->registerRoute("logout", HomeController::class, 'logout');

// Handle request
$route = $_GET['route'] ?? 'home'; // use the usual method to set the default page
$router->handleRequest($route);