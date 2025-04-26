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
        $currentSession = session_id();
        $connectMapData = [
            "connection_ip" => $ipAddress,
            "connection_session" => $currentSession,
            "connection_time" => $currentTime
        ];
        $connectMap = new ConnectionsMapping($connectMapData);
        $checkExisting = $this->connectionsManager->checkRecentConnect($connectMap);
        if(!$checkExisting) {
            $this->connectionsManager->addConnection($connectMap);
        }
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

    public function showLogs($getParams = null) : void
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
        $sortType = $getParams["type"] ?? "all";
        switch ($sortType) {
            case "distinct" :
                $getLogs = $this->connectionsManager->getDistinctLogsForDisplay();
                break;
            default :
                $getLogs = $this->connectionsManager->getAllLogsForDisplay();
                break;
        }
        $logCount = $this->connectionsManager->getLogCounts();
        $libCount = $this->connectionsManager->getLibrelCount();
        echo $this->twig->render("private/private.logs.html.twig", [
            'sessionRole' => $sessionRole,
            'getLogs' => $getLogs,
            'logCount' => $logCount,
            'libCount' => $libCount,
        ]);
    }

    public function logout() : void
    {
        $this->connectionsManager->logoutUser();
        header("Location: ./");
        exit;
    }
}