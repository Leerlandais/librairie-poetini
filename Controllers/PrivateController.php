<?php

namespace Controllers;

use Controllers\AbstractController;

class PrivateController extends AbstractController
{
    public function showLogs($getParams = null) : void
    {
        $sessionRole = $_SESSION['role'] ?? null;
        if(isset($_POST["password"])){
            $pass = $this->simpleTrim($_POST["password"]);
            $passCheck = $this->connectionsManager->checkPassword($pass);
                $ip = $_SERVER['REMOTE_ADDR'];
                if ($ip === "::1") $ip = "127.0.0.1";
            if(!$passCheck){
                $this->logsManager->recordLoginAttempt($ip, false);
                header("location: ?route=home");
            }else {
                $_SESSION['role'] = true;
                $this->logsManager->recordLoginAttempt($ip, true);
                header("location: ?route=displayAll");
            }
        }
        $sortType = $getParams["type"] ?? "all";
        $isDistinct = false;
        switch ($sortType) {
            case "distinct" :
                $getLogs = $this->logsManager->getDistinctLogsForDisplay();
                $isDistinct = true;
                break;
            default :
                $getLogs = $this->logsManager->getAllLogsForDisplay();
                break;
        }
        $logCount = $this->logsManager->getLogCounts();
        $libCount = $this->logsManager->getLibrelCount();
        echo $this->twig->render("private/private.logs.html.twig", [
            'sessionRole' => $sessionRole,
            'getLogs' => $getLogs,
            'logCount' => $logCount,
            'libCount' => $libCount,
            'isDistinct' => $isDistinct,
        ]);
    }

    public function showLogin($getParams = null) : void
    {
        $sessionLogin = $_SESSION['role'] ?? null;
        if(!isset($sessionLogin) || $sessionLogin !== true){
            $_SESSION["errorMessage"] = "Go Away !!!";
            header("location: ?route=home");
        }

        $loginData = $this->logsManager->getLoginDetails();

        echo $this->twig->render("private/private.logins.html.twig", [
            'sessionRole' => $sessionLogin,
            'loginData' => $loginData,
        ]);
    }

    public function logout() : void
    {
        $this->connectionsManager->logoutUser();
        header("Location: ./");
        exit;
    }
}