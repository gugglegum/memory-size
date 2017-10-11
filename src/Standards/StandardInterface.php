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

}
