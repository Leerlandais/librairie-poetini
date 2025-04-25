<?php

namespace model\Mapping;

use DateTime;
use model\Abstract\AbstractMapping;

class ConnectionsMapping extends AbstractMapping
{
    private ?int $connection_id;
    private string $connection_ip;
    private string $connection_session;
    private string|DateTime  $connection_time;
    private ?bool $connection_librel;

    public function getConnectionId(): int
    {
        return $this->connection_id;
    }

    public function setConnectionId(int $connection_id): void
    {
        $connection_id = $this->intClean($connection_id);
        $this->connection_id = $connection_id;
    }

    public function getConnectionIp(): string
    {
        return $this->connection_ip;
    }

    public function setConnectionIp(string $connection_ip): void
    {
        $connection_ip = $this->simpleTrim($connection_ip);
        $this->connection_ip = $connection_ip;
    }

    public function getConnectionSession(): string
    {
        return $this->connection_session;
    }

    public function setConnectionSession(string $connection_session): void
    {
        $connection_session = $this->simpleTrim($connection_session);
        $this->connection_session = $connection_session;
    }

    public function getConnectionTime() :string|DateTime
    {
        return $this->connection_time;
    }

    public function setConnectionTime($connection_time): void
    {
        $this->connection_time = $connection_time;
    }
    public function isConnectionLibrel(): bool
    {
        return $this->connection_librel;
    }

    public function setConnectionLibrel(bool $connection_librel): void
    {
        $this->connection_librel = $connection_librel;
    }


}