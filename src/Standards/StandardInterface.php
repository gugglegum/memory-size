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
     * Returns measurement unit information
     *
     * @param string $prefixedUnit
     * @return array|bool
     */
    public function getUnitInfo(string $prefixedUnit);

}
