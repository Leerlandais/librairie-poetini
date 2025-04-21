<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;

class ConnectionsManager extends AbstractManager
{
    public function addConnection(ConnectionsMapping $connectionsMapping) : void
    {
        $stmt = $this->db->prepare("INSERT INTO `connections`(`connection_ip`, `connection_time`) 
                                            VALUES (:ip, :time)");
        $stmt->bindValue(":ip", $connectionsMapping->getConnectionsIp());
        $stmt->bindValue(":time", $connectionsMapping->getConnectionsTime());
        $stmt->execute();

    }
}