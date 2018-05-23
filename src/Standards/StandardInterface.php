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
     * Resolves unit of measure into multiplier. Return FALSE if unable to resolve. This method is used only in the Parser.
     *
     * @param string        $unit
     * @return float|int|false
     */
    public function unitToMultiplier(string $unit);

    /**
     * Returns associative array of measurement units where keys are units and values are multipliers corresponding to
     * the units. For example: [ 'B' => 1, 'KiB' => 1024, 'MiB' => 1048576, ... ] This method is used only in the Formatter,
     * only these measurement units will be used in formatted memory size.
     *
     * @return array
     */
    public function getByteUnitMultipliers(): array;
}
