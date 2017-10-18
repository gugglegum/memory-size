<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\IEC;
use gugglegum\MemorySize\Standards\StandardInterface;

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
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        // Initialize options
        $this->options = new FormatterOptions($options);

        // If option "standard" not defined - set default;
        if ($this->options->getStandard() === null) {
            $this->options->setStandard($this->getDefaultStandard());
        }
    }

    /**
     * Returns a standard used by default
     *
     * @return StandardInterface
     */
    public function getDefaultStandard(): StandardInterface
    {
        return new IEC();
    }

    /**
     * Formats size of memory or file
     *
     * @param int|float $size Size in bytes
     * @param array     $overrideOptions
     * @return string
     * @throws Exception
     */
    public function format($size, array $overrideOptions = []): string
    {
        if (!empty($overrideOptions)) {
            $options = clone $this->options;
            $options->setFromArray($overrideOptions);
        } else {
            $options = $this->options;
        }

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
        return $this->formatNumber($bestValue, $options) . $options->getUnitSeparator() . $bestUnit;
    }

    /**
     * @param int|float        $number
     * @param FormatterOptions $options
     * @return bool|string
     */
    private function formatNumber($number, FormatterOptions $options)
    {
        $formattedNumber = number_format($number, $options->getMaxDecimals(), $options->getDecimalPoint(), $options->getThousandsSeparator());

        $formattedNumberLength = strlen($formattedNumber);
        $stripMaxTrailingZeros = $options->getMaxDecimals() - $options->getMinDecimals();
        $i = 0;
        while ($formattedNumber{$formattedNumberLength - $i - 1} === '0' && $i < $stripMaxTrailingZeros) {
            $i++;
        }
        if ($formattedNumber{$formattedNumberLength - $i - 1} === $options->getDecimalPoint()) {
            $i++;
        }
        return substr($formattedNumber, 0, $formattedNumberLength - $i);
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
     * @throws Exception
     */
    public function setOptions(array $options)
    {
        $this->options->setFromArray($options);
    }
}
