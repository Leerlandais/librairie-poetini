<?php
namespace model\Trait;
trait TraitTestInt
{
    protected function verifyInt ($testThis, $min = 0, $max = PHP_INT_MAX) : bool{
        if ($testThis < $min || $testThis > $max) return false;
        return true;
    }
}