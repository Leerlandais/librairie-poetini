<?php

namespace Controllers;

use model\Mapping\ConnectionsMapping;

class HomeController extends AbstractController{

    public function index() {
    global $sessionRole, $errorMessage;

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if($ipAddress === "::1") $ipAddress = "127.0.0.1";
        $connectMapData = [
            "connections_ip" => $ipAddress,
        ];
        $connectMap = new ConnectionsMapping($connectMapData);
        $this->connectionsManager->addConnection($connectMap);
        echo $this->twig->render("public/public.index.html.twig", [
            'sessionRole' => $sessionRole,
            'errorMessage' => $errorMessage,

        ]);
    }


}
