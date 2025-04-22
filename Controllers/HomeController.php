<?php

namespace Controllers;

use model\Mapping\ConnectionsMapping;
use DateTime;

class HomeController extends AbstractController
{

    public function index()
    {
        global $sessionRole, $errorMessage;

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if ($ipAddress === "::1") $ipAddress = "127.0.0.1";
        $currentTime = new DateTime();
        $currentTime = $currentTime->format('Y-m-d H:i:s');
        $connectMapData = [
            "connection_ip" => $ipAddress,
            "connection_time" => $currentTime
        ];
        $connectMap = new ConnectionsMapping($connectMapData);
        $this->connectionsManager->addConnection($connectMap);
        echo $this->twig->render("public/public.index.html.twig", [
            'sessionRole' => $sessionRole,
            'errorMessage' => $errorMessage,

        ]);
    }

    public function trackClick()
    {
            $linkId = $_POST['id'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            if ($ipAddress === "::1") $ipAddress = "127.0.0.1";
            $this->connectionsManager->logLibrelClick($linkId,$ipAddress);

            http_response_code(204);
    }

    public function notFound() : void
    {
        echo $this->twig->render("err404.html.twig", []);
    }

    public function showLogs() : void
    {
        $sessionRole = $_SESSION['role'] ?? null;
        if(isset($_POST["password"])){
            $pass = $this->simpleTrim($_POST["password"]);
            $passCheck = $this->connectionsManager->checkPassword($pass);
            if(!$passCheck){
                header("location: ?route=home");
            }else {
                $_SESSION['role'] = true;
                header("location: ?route=displayAll");
            }
        }

        $getLogs = $this->connectionsManager->getAllLogsForDisplay();
        echo $this->twig->render("private/private.logs.html.twig", [
            'sessionRole' => $sessionRole,
            'getLogs' => $getLogs
        ]);
    }
}