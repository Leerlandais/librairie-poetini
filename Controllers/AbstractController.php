<?php

namespace Controllers;

use model\Manager\ConnectionsManager;
use model\MyPDO;
use Twig\Environment;
use model\Trait\TraitLaundryRoom;

// As with Manager and Mapping, the Controllers have lots of shared needs, so Abstract to keep it DRY

abstract class AbstractController
{
    use TraitLaundryRoom;
    protected $twig;
    protected $connectionsManager;
    protected MyPDO $db;
    public function __construct(Environment $twig, MyPDO $db)
    {
        $this->twig = $twig;
        $this->db = $db;
        $this->connectionsManager = new ConnectionsManager($db);
    }
}
