<?php

namespace Controllers;

use model\Mapping\ConnectionsMapping;
use DateTime;

class HomeController extends AbstractController{

    public function index() {
    global $sessionRole, $errorMessage;

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if($ipAddress === "::1") $ipAddress = "127.0.0.1";
        $currentTime = new DateTime();
        $currentTime = $currentTime->format('Y-m-d H:i:s');
        $connectMapData = [
            "connections_ip" => $ipAddress,
            "connections_time" => $currentTime
        ];
        $connectMap = new ConnectionsMapping($connectMapData);
        $this->connectionsManager->addConnection($connectMap);
        echo $this->twig->render("public/public.index.html.twig", [
            'sessionRole' => $sessionRole,
            'errorMessage' => $errorMessage,

        ]);
    }


}
