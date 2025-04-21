<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;

class ConnectionsManager extends AbstractManager
{
    public function addConnection(ConnectionsMapping $connectionsMapping) : void
    {
        $stmt = $this->db->prepare("INSERT INTO `connections`(`connection_ip`) VALUES (:ip)");
        $stmt->bindValue(":ip", $connectionsMapping->getConnectionsIp());
        $stmt->execute();

    }
}