<?php

declare(strict_types=1);

namespace gugglegum\MemorySize\Standards;

/**
 * Implementation of information size measure by ISO/IEC 80000
 *
 * @see https://en.wikipedia.org/wiki/ISO/IEC_80000
 * @package gugglegum\MemorySize\Standards
 */
class IEC implements StandardInterface
{
    use StandardTrait;

    /**
     * Associative array where keys are measure units and values are arrays of 3 elements: [coefficient, base of degree
     * and exponent]
     *
     * @var array
     */
    protected static $unitsInfo = [
        'KiB' => [1, 1024, 1], // Kibibyte (1024^1 bytes)
        'MiB' => [1, 1024, 2], // Mebibyte (1024^2 bytes)
        'GiB' => [1, 1024, 3], // Gibibyte (1024^3 bytes)
        'TiB' => [1, 1024, 4], // Tebibyte (1024^4 bytes)
        'PiB' => [1, 1024, 5], // Pebibyte (1024^5 bytes)
        'EiB' => [1, 1024, 6], // Exbibyte (1024^6 bytes)
        'ZiB' => [1, 1024, 7], // Zebibyte (1024^7 bytes)
        'YiB' => [1, 1024, 8], // Yobibyte (1024^8 bytes)

        'Kibit' => [1/8, 1024, 1], // Kibibit (1024^1 bits)
        'Mibit' => [1/8, 1024, 2], // Mebibit (1024^2 bits)
        'Gibit' => [1/8, 1024, 3], // Gibibit (1024^3 bits)
        'Tibit' => [1/8, 1024, 4], // Tebibit (1024^4 bits)
        'Pibit' => [1/8, 1024, 5], // Pebibit (1024^5 bits)
        'Eibit' => [1/8, 1024, 6], // Exbibit (1024^6 bits)
        'Zibit' => [1/8, 1024, 7], // Zebibit (1024^7 bits)
        'Yibit' => [1/8, 1024, 8], // Yobibit (1024^8 bits)
    ];

}
