<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Formatter options
 *
 * @package gugglegum\MemorySize
 */
class FormatterOptions
{
    /**
     * Standard to use in formatting
     *
     * @var StandardInterface
     */
    private $standard;

    /**
     * Min amount of digits after "." (makes sense to set equal to $maxDecimals to make fixed size of decimal fraction)
     *
     * @var int
     */
    private $minDecimals = 0;

    /**
     * Max amount of digits after "."
     *
     * @var int
     */
    private $maxDecimals = 2;

    /**
     * Decimal point that splits integer and fractional parts
     *
     * @var string
     */
    private $decimalPoint = '.';

    /**
     * Separator for thousands, millions, billions, trillions, etc.
     *
     * @var string
     */
    private $thousandsSeparator = '';

    /**
     * Separator between number and measurement unit. Usually space (" ") or empty ("")
     *
     * @var string
     */
    private $unitSeparator = ' ';

    /**
     * Constructor allows to initialize attribute values
     *
     * @param array $data           Associative array with [attribute => value] pairs
     * @throws Exception
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return self
     * @throws Exception
     */
    public function setFromArray(array $data): self
    {
        foreach ($data as $k => $v) {
            switch ($k) {
                case 'standard' :
                    $this->setStandard($v);
                    break;
                case 'minDecimals' :
                    $this->setMinDecimals($v);
                    break;
                case 'maxDecimals' :
                    $this->setMaxDecimals($v);
                    break;
                case 'fixedDecimals' :
                    $this->setFixedDecimals($v);
                    break;
                case 'decimalPoint' :
                    $this->setDecimalPoint($v);
                    break;
                case 'thousandsSeparator' :
                    $this->setThousandsSeparator($v);
                    break;
                case 'unitSeparator' :
                    $this->setUnitSeparator($v);
                    break;
                default :
                    throw new Exception("Unknown memory-size formatter option \"{$k}\"");
            }
        }
        return $this;
    }

    /**
     * @return StandardInterface|null
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param StandardInterface $standard
     * @return FormatterOptions
     * @throws Exception
     */
    public function setStandard(StandardInterface $standard): FormatterOptions
    {
        $this->standard = $standard;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinDecimals(): int
    {
        return $this->minDecimals;
    }

    /**
     * @param int $minDecimals
     * @return FormatterOptions
     */
    public function setMinDecimals(int $minDecimals): FormatterOptions
    {
        $this->minDecimals = $minDecimals;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxDecimals(): int
    {
        return $this->maxDecimals;
    }

    /**
     * @param int $maxDecimals
     * @return FormatterOptions
     */
    public function setMaxDecimals(int $maxDecimals): FormatterOptions
    {
        $this->maxDecimals = $maxDecimals;
        return $this;
    }

    /**
     * A shorthand for setMinDecimals() & setMaxDecimals() at once. Useful for cases when you need fixed size of
     * fractional part.
     *
     * @param int $decimals
     * @return FormatterOptions
     */
    public function setFixedDecimals(int $decimals): FormatterOptions
    {
        $this->setMinDecimals($decimals);
        $this->setMaxDecimals($decimals);
        return $this;
    }

    /**
     * @return string
     */
    public function getDecimalPoint(): string
    {
        return $this->decimalPoint;
    }

    /**
     * @param string $decimalPoint
     * @return FormatterOptions
     */
    public function setDecimalPoint(string $decimalPoint): FormatterOptions
    {
        $this->decimalPoint = $decimalPoint;
        return $this;
    }

    /**
     * @return string
     */
    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator;
    }

    /**
     * @param string $thousandsSeparator
     * @return FormatterOptions
     */
    public function setThousandsSeparator(string $thousandsSeparator): FormatterOptions
    {
        $this->thousandsSeparator = $thousandsSeparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnitSeparator(): string
    {
        return $this->unitSeparator;
    }

    /**
     * @param string $unitSeparator
     * @return FormatterOptions
     */
    public function setUnitSeparator(string $unitSeparator): FormatterOptions
    {
        $this->unitSeparator = $unitSeparator;
        return $this;
    }
}
