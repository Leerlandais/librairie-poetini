<?php
namespace model\Abstract;
use model\MyPDO;
abstract class AbstractManager {
    protected MyPDO $db;
    public function __construct(MyPDO $db) {
        $this->db = $db;
    }
        }