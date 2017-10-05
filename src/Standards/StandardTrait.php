<?php

namespace gugglegum\MemorySize\Standards;

/**
 * Standards helper trait
 *
 * @package gugglegum\MemorySize\Standards
 */
trait StandardTrait
{
    /**
     * Returns measurement unit information
     *
     * @param string $prefixedUnit
     * @return array|bool
     */
    public function getUnitInfo(string $prefixedUnit)
    {
        if (array_key_exists($prefixedUnit, static::$unitsInfo)) {
            return [
                'coefficient' => static::$unitsInfo[$prefixedUnit][0],
                'base' => static::$unitsInfo[$prefixedUnit][1],
                'exp' => static::$unitsInfo[$prefixedUnit][2],
            ];
        } else {
            return false;
        }
    }
}
