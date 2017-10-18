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

        // Binary prefixes:

        'B'   => [1, 1, 1],    // Byte
        'KiB' => [1, 1024, 1], // Kibibyte (1024^1 bytes)
        'MiB' => [1, 1024, 2], // Mebibyte (1024^2 bytes)
        'GiB' => [1, 1024, 3], // Gibibyte (1024^3 bytes)
        'TiB' => [1, 1024, 4], // Tebibyte (1024^4 bytes)
        'PiB' => [1, 1024, 5], // Pebibyte (1024^5 bytes)
        'EiB' => [1, 1024, 6], // Exbibyte (1024^6 bytes)
        'ZiB' => [1, 1024, 7], // Zebibyte (1024^7 bytes)
        'YiB' => [1, 1024, 8], // Yobibyte (1024^8 bytes)

        'bit'   => [1/8, 1, 1],    // Bit
        'Kibit' => [1/8, 1024, 1], // Kibibit (1024^1 bits)
        'Mibit' => [1/8, 1024, 2], // Mebibit (1024^2 bits)
        'Gibit' => [1/8, 1024, 3], // Gibibit (1024^3 bits)
        'Tibit' => [1/8, 1024, 4], // Tebibit (1024^4 bits)
        'Pibit' => [1/8, 1024, 5], // Pebibit (1024^5 bits)
        'Eibit' => [1/8, 1024, 6], // Exbibit (1024^6 bits)
        'Zibit' => [1/8, 1024, 7], // Zebibit (1024^7 bits)
        'Yibit' => [1/8, 1024, 8], // Yobibit (1024^8 bits)

        // Decimal (metric/SI) prefixes:

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

    /**
     * Array of measurement units used in formatting
     *
     * @var array
     */
    protected static $byteUnits = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
}
