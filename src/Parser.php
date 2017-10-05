<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\Common;
use gugglegum\MemorySize\Standards\IEC;
use gugglegum\MemorySize\Standards\JEDEC;
use gugglegum\MemorySize\Standards\SI;
use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Parses file or memory size in human-friendly format (like "1.44 MB" or "4.38 GiB) and returns normalized size in
 * bytes. Supports 4 primary information size measurement standards:
 *
 *   - Common (not a standard actually, just a trick to parse simple units without prefixes like "128 B" (128 bytes)
 *     or "16 b" (16 bits) in a common way
 *   - JEDEC Standard 100B.01 (Binary "K" or "KB", "M" or "MB", "G" or "GB", "Kbit", "Mbit", "Gbit")
 *   - ISO/IEC 80000 (Binary "KiB", "MiB", "GiB", "TiB", etc. Also "Kibit", "Mibit", "Gibit", etc.)
 *   - SI Standard (Decimal "kB", "MB", "GB", etc.)
 *
 * JEDEC and SI partially conflicts because "1MB" in JEDEC is 1048576 bytes, but in SI "1MB" is 1000000 bytes. Due to
 * historical reasons JEDEC standard has higher priority than SI. But you may change this behaviour if you need it.
 * You can define a list of supported standards in priority descending order and only these standards will be used in
 * parsing. You may even pass your own standard implementation.
 *
 * @package gugglegum\MemorySize
 */
class Parser
{
    /**
     * Parser options
     *
     * @var ParserOptions
     */
    private $options;

    /**
     * Parser constructor
     *
     * @param array $options    OPTIONAL associative array of options to override defaults
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        try {
            // Initialize options
            $this->options = new ParserOptions($options);

            // If options "standards" not defined - set default list;
            if ($this->options->getAttribute('standards') === null) {
                $this->options->setAttribute('standards', $this->getDefaultStandards());
            }
        } catch (\gugglegum\AbstractEntity\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns a list of standards used by default in descending priority order
     *
     * @return StandardInterface[]
     */
    public function getDefaultStandards()
    {
        return [
            new Common(),
            new JEDEC(),
            new IEC(),
            new SI(),
        ];
    }

    /**
     * Returns parser options instance
     *
     * @return ParserOptions
     */
    public function getOptions(): ParserOptions
    {
        return $this->options;
    }

    /**
     * Set options from associative array
     *
     * @param array $options
     * @throws Exception
     */
    public function setOptions(array $options)
    {
        try {
            $this->options->setFromArray($options);
        } catch (\gugglegum\AbstractEntity\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Parses human-friendly file/memory size to normalized size in bytes
     *
     * @param string $formattedSize
     * @param array  $overrideOptions
     * @return float|int
     * @throws Exception
     */
    public function parse(string $formattedSize, array $overrideOptions = [])
    {
        try {
            if (!empty($overrideOptions)) {
                $options = clone $this->options;
                $options->setFromArray($overrideOptions);
            } else {
                $options = $this->options;
            }
        } catch (\gugglegum\AbstractEntity\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        $data = $this->splitNumberAndUnit($formattedSize, $options);
        return $data['number'] * $this->resolveUnitToMultiplier($data['unit'], $options);
    }

    /**
     * Splits formatted size into number and unit of measure
     *
     * @param string        $formattedSize
     * @param ParserOptions $options
     * @return array
     * @throws Exception
     */
    private function splitNumberAndUnit(string $formattedSize, ParserOptions $options): array
    {
        // Matches "12", "12.34" and if $allowNegativeSize "-23.45"
        $numberPattern = ($options->isAllowNegative() ? '-?' : '') . '\d+(?:\.\d+)?';
        // Matches "KB", "MiB", etc.
        $unitPattern = "[a-z]+";
        // Matches "1.44 MiB", "4.7GB"
        if (preg_match("/^({$numberPattern})\s*({$unitPattern})?$/i", $formattedSize, $m)) {
            return [
                'number' => $m[1],
                'unit' => isset($m[2]) ? $m[2] : '',
            ];
        } else {
            throw new Exception('Failed to parse formatted memory size');
        }
    }

    /**
     * Resolves unit of measure into multiplier
     *
     * @param string        $unit
     * @param ParserOptions $options
     * @return float|int
     * @throws Exception
     */
    private function resolveUnitToMultiplier(string $unit, ParserOptions $options)
    {
        if ($unit === '') {
            return 1;
        }
        foreach ($options->getStandards() as $standard) {
            if (($unitInfo = $standard->getUnitInfo($unit)) !== false) {
                return $unitInfo['coefficient'] * pow($unitInfo['base'], $unitInfo['exp']);
            }
        }
        throw new Exception("Failed to recognize information measurement unit \"{$unit}\"");
    }

}
