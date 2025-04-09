<?php

namespace Controllers;

use model\MyPDO;
use Twig\Environment;

// As with Manager and Mapping, the Controllers have lots of shared needs, so Abstract to keep it DRY

abstract class AbstractController
{
    protected $twig;

    protected MyPDO $db;
    public function __construct(Environment $twig, MyPDO $db)
    {
        $this->twig = $twig;

        $this->db = $db;
    }
}
