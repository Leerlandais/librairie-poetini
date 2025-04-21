<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class ConnectionsMapping extends AbstractMapping
{
    private ?int $connections_id;
    private string $connections_ip;
    private ?bool $connections_librel;

    public function getConnectionsId(): int
    {
        return $this->connections_id;
    }

    public function setConnectionsId(int $connections_id): void
    {
        $connections_id = $this->intClean($connections_id);
        $this->connections_id = $connections_id;
    }

    public function getConnectionsIp(): string
    {
        return $this->connections_ip;
    }

    public function setConnectionsIp(string $connections_ip): void
    {
        $connections_ip = $this->simpleTrim($connections_ip);
        $this->connections_ip = $connections_ip;
    }

    public function isConnectionsLibrel(): bool
    {
        return $this->connections_librel;
    }

    public function setConnectionsLibrel(bool $connections_librel): void
    {
        $this->connections_librel = $connections_librel;
    }


}