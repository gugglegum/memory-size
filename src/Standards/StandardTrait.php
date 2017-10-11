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
     * Resolves unit of measure into multiplier
     *
     * @param string        $unit
     * @return float|int|false
     */
    public function unitToMultiplier(string $unit)
    {
        if (array_key_exists($unit, static::$unitsInfo)) {
            list($coefficient, $base, $exp) = static::$unitsInfo[$unit];
            return $coefficient * pow($base, $exp);
        } else {
            return false;
        }
    }
}
