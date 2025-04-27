<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;
use Exception;
use model\MyPDO;
use DateTime;
use PDO;

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
    public function checkPassword(string $password) : bool
    {
        return password_verify($password, password_hash(LOG_PASS, PASSWORD_DEFAULT));
    }

    public function logoutUser() : void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }


}