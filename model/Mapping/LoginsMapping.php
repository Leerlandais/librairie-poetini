<?php

namespace model\Mapping;

use model\Abstract\AbstractMapping;

class LoginsMapping extends AbstractMapping
{
    private ?int $login_id;
    private string $login_ip;
    private string $login_date;
    private bool $login_success;

    public function getLoginId(): ?int
    {
        return $this->login_id;
    }

    public function setLoginId(?int $login_id): void
    {
        if(isset($login_id)) {
            $login_id = $this->intClean($login_id);
        }
        $this->login_id = $login_id;
    }

    public function getLoginIp(): string
    {
        return $this->login_ip;
    }

    public function setLoginIp(string $login_ip): void
    {
        $login_ip = $this->standardClean($login_ip);
        $this->login_ip = $login_ip;
    }

    public function getLoginDate(): string
    {
        return $this->login_date;
    }

    public function setLoginDate(string $login_date): void
    {
        $login_date = $this->standardClean($login_date);
        $this->login_date = $login_date;
    }



    public function isLoginSuccess(): bool
    {
        return $this->login_success;
    }

    public function setLoginSuccess(bool $login_success): void
    {
        $this->login_success = $login_success;
    }


}