<?php

declare(strict_types=1);

namespace gugglegum\MemorySize\Standards;

/**
 * Implementation of information size measure by Metric system (SI)
 *
 * @see https://en.wikipedia.org/wiki/SI_prefix
 * @package gugglegum\MemorySize\Standards
 */
class SI implements StandardInterface
{
    use StandardTrait;

    /**
     * Associative array where keys are measure units and values are arrays of 3 elements: [coefficient, base of degree
     * and exponent]
     *
     * @var array
     */
    protected static $unitsInfo = [
        'kB' => [1, 1000, 1], // Kilobyte (1000 bytes)
        'MB' => [1, 1000, 2], // Megabyte (1000^2 bytes)
        'GB' => [1, 1000, 3], // Gigabyte (1000^3 bytes)
        'TB' => [1, 1000, 4], // Terabyte (1000^4 bytes)
        'PB' => [1, 1000, 5], // Petabyte (1000^5 bytes)
        'EB' => [1, 1000, 6], // Exabyte (1000^6 bytes)
        'ZB' => [1, 1000, 7], // Zettabyte (1000^7 bytes)
        'YB' => [1, 1000, 8], // Yottabyte (1000^8 bytes)

        'kbit' => [1/8, 1000, 1], // Kilobit (1000 bits)
        'Mbit' => [1/8, 1000, 2], // Megabit (1000^2 bits)
        'Gbit' => [1/8, 1000, 3], // Gigabit (1000^3 bits)
        'Tbit' => [1/8, 1000, 4], // Terabit (1000^4 bits)
        'Pbit' => [1/8, 1000, 5], // Petabit (1000^5 bits)
        'Ebit' => [1/8, 1000, 6], // Exabit (1000^6 bits)
        'Zbit' => [1/8, 1000, 7], // Zettabit (1000^7 bits)
        'Ybit' => [1/8, 1000, 8], // Yottabit (1000^8 bits)
    ];

}
