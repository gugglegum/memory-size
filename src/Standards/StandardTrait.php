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

    /**
     * Returns associative array of measurement units where keys are units and values are multipliers corresponding to
     * the units. For example: [ 'B' => 1, 'KiB' => 1024, 'MiB' => 1048576, ... ]
     *
     * @return array
     */
    public function getByteUnitMultipliers(): array
    {
        $unitMultipliers = [];
        foreach (self::$byteUnits as $unit) {
            $unitMultipliers[$unit] = pow(self::$unitsInfo[$unit][1], self::$unitsInfo[$unit][2]);
        }
        return $unitMultipliers;
    }
}
