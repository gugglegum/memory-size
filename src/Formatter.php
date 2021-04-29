<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\IEC;
use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Formats memory sizes in human-friendly view
 *
 * @package gugglegum\MemorySize
 */
class Formatter
{
    /**
     * Parser options
     *
     * @var FormatterOptions
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
        $this->options = new FormatterOptions($options);
    }

    /**
     * Returns a standard used by default
     *
     * @return StandardInterface
     */
    public static function getDefaultStandard(): StandardInterface
    {
        return new IEC();
    }

    /**
     * Formats size of memory or file
     *
     * @param int|float $size Size in bytes
     * @param array $overrideOptions Formatter options to override (only for one time)
     * @return string
     * @throws Exception
     * @throws \InvalidArgumentException
     */
    public function format($size, array $overrideOptions = []): string
    {
        if (!empty($overrideOptions)) {
            $options = clone $this->options;
            $options->setFromArray($overrideOptions);
        } else {
            $options = $this->options;
        }
        $options->lazyInitialization();

        $standard = $options->getStandard();
        $multipliers = $standard->getByteUnitMultipliers();

        $bestValue = null;
        $bestUnit = null;
        $minAbsValueButNotLessThanOne = null;
        foreach ($multipliers as $unit => $multiplier) {
            $absValue = abs($size / $multiplier);
            if ($minAbsValueButNotLessThanOne === null || ($absValue < $minAbsValueButNotLessThanOne && $absValue >= 1)) {
                $minAbsValueButNotLessThanOne = $absValue;
                $bestValue = $size / $multiplier;
                $bestUnit = $unit;
            }
        }

        if ($bestValue === null || $bestUnit === null) {
            throw new Exception("Failed to format memory size {$size}");
        }

        return $this->formatNumber($bestValue, $options) . $options->getUnitSeparator() . $bestUnit;
    }

    /**
     * Formats a number representing size value
     *
     * @param int|float $number
     * @param FormatterOptions $options
     * @return string
     */
    private function formatNumber($number, FormatterOptions $options): string
    {
        $formattedNumber = number_format(
            $number,
            $options->getMaxDecimals(),
            $options->getNumberFormat()->getDecimalPoint(),
            $options->getNumberFormat()->getThousandsSeparator()
        );

        $formattedNumberLength = strlen($formattedNumber);
        $stripMaxTrailingZeros = $options->getMaxDecimals() - $options->getMinDecimals();
        $i = 0;
        while ($formattedNumber[$formattedNumberLength - $i - 1] === '0' && $i < $stripMaxTrailingZeros) {
            $i++;
        }
        if ($formattedNumber[$formattedNumberLength - $i - 1] === $options->getNumberFormat()->getDecimalPoint()) {
            $i++;
        }
        return (string) substr($formattedNumber, 0, $formattedNumberLength - $i);
    }

    /**
     * Returns parser options instance
     *
     * @return FormatterOptions
     */
    public function getOptions(): FormatterOptions
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
}
