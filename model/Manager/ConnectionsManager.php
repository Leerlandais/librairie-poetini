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

    public function getLogCounts() : array
    {
        $query = $this->db->query("SELECT `connection_time` FROM `connections` WHERE `connection_ip` != '83.134.101.191'");
        $logs = $query->fetchAll(PDO::FETCH_COLUMN);
        $query->closeCursor();

        $now = new DateTime();
        $counts = [
            "conn_day" => 0,
            "conn_week" => 0,
            "conn_month" => 0,
            "conn_total" => count($logs)
        ];

        foreach ($logs as $time) {
            $logTime = new DateTime($time);

            if ($logTime->format('Y-m-d') === $now->format('Y-m-d')) {
                $counts["conn_day"]++;
            }

            if ($logTime->format('o-W') === $now->format('o-W')) {
                $counts["conn_week"]++;
            }

            if ($logTime->format('Y-m') === $now->format('Y-m')) {
                $counts["conn_month"]++;
            }
        }
        return $counts;
    }

    public function getLibrelCount() : array
    {
        $query = $this->db->query("SELECT `connection_time` FROM `connections` WHERE `connection_librel` = 1");
        $librel = $query->fetchAll(PDO::FETCH_COLUMN);
        $query->closeCursor();
        $now = new DateTime();
        $counts = [
            "librel_day" => 0,
            "librel_week" => 0,
            "librel_month" => 0,
            "librel_total" => count($librel)
        ];
        foreach ($librel as $time) {
            $logTime = new DateTime($time);

            if ($logTime->format('Y-m-d') === $now->format('Y-m-d')) {
                $counts["librel_day"]++;
            }

            if ($logTime->format('o-W') === $now->format('o-W')) {
                $counts["librel_week"]++;
            }

            if ($logTime->format('Y-m') === $now->format('Y-m')) {
                $counts["librel_month"]++;
            }
        }
        return $counts;
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