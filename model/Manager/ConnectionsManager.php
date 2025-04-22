<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;
use Exception;

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

    public function logLibrelClick($id, $ip) : void
    {
        try {
            $query = $this->db->prepare("SELECT * FROM `connections` WHERE `connection_ip` = :ip ORDER BY `connection_id` DESC LIMIT 1");
            $query->bindValue(":ip", $ip);
            $query->execute();
            $result = $query->fetch();
            $userId = $result["connection_id"];
            $stmt = $this->db->prepare("UPDATE `connections` SET `connection_librel`= 1 WHERE `connection_id` = ?");
            $stmt->bindValue(1, $userId);
            $stmt->execute();
        }catch (Exception $e){
            $_SESSION["click-message"] = $e->getMessage();
            header("location: /");
        }

    }
}