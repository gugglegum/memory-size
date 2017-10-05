<?php

declare(strict_types=1);

namespace gugglegum\MemorySize\Standards;

/**
 * Common standard of information size measurement (without any prefixes)
 *
 * @package gugglegum\MemorySize\Standards
 */
class Common implements StandardInterface
{
    use StandardTrait;

    /**
     * Associative array where keys are measure units and values are arrays of 3 elements: [coefficient, base of degree
     * and exponent]
     *
     * @var array
     */
    protected static $unitsInfo = [
        'B' => [1, 1, 1],     // Byte
        'b' => [1/8, 1, 1],   // Bit
    ];
}
