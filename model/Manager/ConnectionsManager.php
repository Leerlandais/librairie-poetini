<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;
use Exception;

class ConnectionsManager extends AbstractManager
{
    public function checkRecentConnect(ConnectionsMapping $connectMap) : bool
    {
        $stmt = $this->db->prepare("SELECT * FROM `connections` WHERE `connection_ip`= ? AND `connection_session` = ?");
        $stmt->bindValue(1, $connectMap->getConnectionIp());
        $stmt->bindValue(2, $connectMap->getConnectionSession());
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }
    public function addConnection(ConnectionsMapping $connectionsMapping) : void
    {
        $stmt = $this->db->prepare("INSERT INTO `connections`(`connection_ip`,  `connection_session`,  `connection_time`) 
                                            VALUES (:ip, :sess, :time)");
        $stmt->bindValue(":ip", $connectionsMapping->getConnectionIp());
        $stmt->bindValue(":sess", $connectionsMapping->getConnectionSession());
        $stmt->bindValue(":time", $connectionsMapping->getConnectionTime());
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

    public function getAllLogsForDisplay() : array
    {
        $query = $this->db->query("SELECT * FROM `connections` ORDER BY `connection_id` DESC");
        $logMap = [];
        while($result = $query->fetch()){
            $logMap[] = new ConnectionsMapping($result);
        }
        return $logMap;
    }

    public function checkPassword(string $password) : bool
    {
        return password_verify($password, password_hash(LOG_PASS, PASSWORD_DEFAULT));
    }
}