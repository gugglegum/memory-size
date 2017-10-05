<?php

declare(strict_types=1);

namespace gugglegum\MemorySize\Standards;

/**
 * Implementation of information size measure by JEDEC Standard 100B.01
 *
 * @see https://en.wikipedia.org/wiki/JEDEC_memory_standards
 * @package gugglegum\MemorySize\Standards
 */
class JEDEC implements StandardInterface
{
    use StandardTrait;

    /**
     * Associative array where keys are measure units and values are arrays of 3 elements: [coefficient, base of degree
     * and exponent]
     *
     * @var array
     */
    protected static $unitsInfo = [
        'K' =>  [1, 1024, 1], // Kibibyte (1024^1 bytes)
        'KB' => [1, 1024, 1], // Kibibyte (1024^1 bytes)
        'M' =>  [1, 1024, 2], // Mebibyte (1024^2 bytes)
        'MB' => [1, 1024, 2], // Mebibyte (1024^2 bytes)
        'G' =>  [1, 1024, 3], // Gibibyte (1024^3 bytes)
        'GB' => [1, 1024, 3], // Gibibyte (1024^3 bytes)

        'Kbit' => [1/8, 1024, 1], // Kibibit (1024^1 bits)
        'Mbit' => [1/8, 1024, 2], // Mebibit (1024^2 bits)
        'Gbit' => [1/8, 1024, 3], // Gibibit (1024^3 bits)
    ];
}
