<?php

namespace gugglegum\MemorySize\Standards;

/**
 * Memory size measurement units standard interface
 *
 * @package gugglegum\MemorySize\Standards
 */
interface StandardInterface
{
    /**
     * Resolves unit of measure into multiplier
     *
     * @param string        $unit
     * @return float|int|false
     */
    public function unitToMultiplier(string $unit);

    /**
     * Returns associative array of measurement units where keys are units and values are multipliers corresponding to
     * the units. For example: [ 'B' => 1, 'KiB' => 1024, 'MiB' => 1048576, ... ]
     *
     * @return array
     */
    public function getByteUnitMultipliers(): array;
}
