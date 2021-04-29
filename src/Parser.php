<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\IEC;
use gugglegum\MemorySize\Standards\JEDEC;
use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Parses file or memory size in human-friendly format (like "1.44 MB" or "4.38 GiB) and returns normalized size in
 * bytes. Supports 4 primary information size measurement standards:
 *
 *   - JEDEC Standard 100B.01 (Binary "K" or "KB", "M" or "MB", "G" or "GB", "Kb", "Kbit", "Mb", "Mbit", "Gb", "Gbit")
 *   - ISO/IEC 80000 (Binary "KiB", "MiB", "GiB", "TiB", etc. Also "Kibit", "Mibit", "Gibit", etc.)
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
     * @throws \InvalidArgumentException
     */
    public function __construct(array $options = [])
    {
        // Initialize options
        $this->options = new ParserOptions($options);
    }

    /**
     * Returns a list of standards used by default in descending priority order
     *
     * @return StandardInterface[]
     */
    public static function getDefaultStandards(): array
    {
        return [
            new IEC(),
            new JEDEC(),
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
     * @throws \InvalidArgumentException
     */
    public function setOptions(array $options)
    {
        $this->options->setFromArray($options);
    }

    /**
     * Parses human-friendly file/memory size to normalized size in bytes
     *
     * @param string $formattedSize
     * @param array  $overrideOptions
     * @return float|int
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    public function parse(string $formattedSize, array $overrideOptions = [])
    {
        if (!empty($overrideOptions)) {
            $options = clone $this->options;
            $options->setFromArray($overrideOptions);
        } else {
            $options = $this->options;
        }
        $options->lazyInitialization();

        $data = $this->splitNumberAndUnit($formattedSize, $options);
        return $data['number'] * $this->unitToMultiplier($data['unit'], $options);
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
        $numberFormats = $options->getNumberFormats();
        $numberFormatSubPatterns = [];
        foreach ($numberFormats as $numberFormat) {
            $numberFormatSubPatterns[] = ($options->isAllowNegative() ? '-?' : '') . '\d{1,3}(?:' . preg_quote($numberFormat->getThousandsSeparator()) . '\d{3})*(?:' . preg_quote($numberFormat->getDecimalPoint()) . '\d+)?';
        }
        $numberPattern = '(?:' . implode('|', $numberFormatSubPatterns) . ')';
        // Matches "KB", "MiB", etc.
        $unitPattern = "[a-z]+";
        // Matches "1.44 MiB", "4.7GB"
        if (preg_match("/^({$numberPattern})\s*({$unitPattern})?$/i", $formattedSize, $m)) {

            // Recognize the number and convert it to standard simple form ("12 345,67" => "12345.67")
            $numberParsed = false;
            for ($i = 0; $i < count($numberFormatSubPatterns); $i++) {
                if (preg_match('/^' . $numberFormatSubPatterns[$i] . '$/', $m[1])) {
                    $m[1] = str_replace([
                        $numberFormats[$i]->getDecimalPoint(),
                        $numberFormats[$i]->getThousandsSeparator(),
                    ], [
                        '.',
                        '',
                    ], $m[1]);
                    $numberParsed = true;
                }
            }

            if (!$numberParsed) {
                // This exception actually should never be thrown since we can enter this branch only if regular
                // expression with all number formats are matched.
                throw new Exception("Can't parse number \"{$m[1]}\" (impossible exception)");
            }

            return [
                'number' => $m[1],
                'unit' => $m[2] ?? '',
            ];
        } else {
            throw new Exception('Failed to parse formatted memory size ("' . $formattedSize . '")');
        }
    }

    /**
     * Resolves unit of measure into multiplier. This method actually iterates standards and calls its standard-specific
     * unitToMultiplier(). Usually it returns such values as 1, 1000, 1024, 1000000, 1048576 and so on.
     *
     * @param string        $unit
     * @param ParserOptions $options
     * @return float|int
     * @throws Exception
     */
    private function unitToMultiplier(string $unit, ParserOptions $options)
    {
        if ($unit === '') {
            return 1;
        }
        foreach ($options->getStandards() as $standard) {
            if (($multiplier = $standard->unitToMultiplier($unit)) !== false) {
                return $multiplier;
            }
        }
        throw new Exception("Failed to recognize information measurement unit \"{$unit}\"");
    }

}
