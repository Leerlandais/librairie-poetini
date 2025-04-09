<?php
namespace model\Trait;
trait TraitStringTest
{
    protected function verifyString (?string $testThis) : bool {
        if (empty($testThis)) return false;
        return true;
    }
}