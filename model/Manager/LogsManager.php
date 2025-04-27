<?php

namespace model\Manager;

use model\Abstract\AbstractManager;
use model\Mapping\ConnectionsMapping;
use Exception;
use DateTime;
use model\Mapping\LoginsMapping;
use PDO;

class LogsManager extends AbstractManager
{
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
    public function getDistinctLogsForDisplay() : array
    {
        $query = $this->db->query("SELECT c.*
                                            FROM `connections` c
                                            INNER JOIN (
                                                SELECT `connection_ip`, MAX(`connection_id`) AS max_id
                                                FROM `connections`
                                                GROUP BY `connection_ip`
                                            ) latest ON c.`connection_ip` = latest.`connection_ip` AND c.`connection_id` = latest.max_id
                                            ORDER BY c.`connection_id` DESC;
                                            ");
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

        $counts = $this->extractLogData($logs);
        return $counts;
    }


    public function getLibrelCount() : array
    {
        $query = $this->db->query("SELECT `connection_time` FROM `connections` WHERE `connection_librel` = 1 AND `connection_ip` != '83.134.101.191'");
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

    public function recordLoginAttempt(string $ip, bool $success) : void
    {
        $stmt = $this->db->prepare("INSERT INTO `logins`(`login_ip`, `login_success`) VALUES (:ip, :success)");
        $stmt->bindValue(":ip", $ip);
        $stmt->bindValue(":success", $success, PDO::PARAM_INT);
        $stmt->execute();

    }

    private function extractLogData(array $logs) : array
    {
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

    public function getLoginDetails() : array
    {
        $query = $this->db->query("SELECT * FROM `logins`");
        $loginMap = [];
        while ($result = $query->fetch()){
            $loginMap[] = new LoginsMapping($result);
        }
        return $loginMap;
    }

}