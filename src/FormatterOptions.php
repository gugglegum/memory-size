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
     * Decimal point (usually "." or ",") & thousands separator (usually none or "," or " ")
     *
     * @var NumberFormat
     */
    private $numberFormat;

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
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }

    /**
     * Lazy initialization with default values for required options which are undefined
     */
    public function lazyInitialization()
    {
        // If option "standard" not defined - set default
        if ($this->getStandard() === null) {
            $this->setStandard(Formatter::getDefaultStandard());
        }

        // If option "numberFormat" not defined - set default
        if (!$this->hasNumberFormat()) {
            $this->setNumberFormat(NumberFormat::createDefault());
        }
    }

    /**
     * Initializes the model by values from associative array. Only attributes corresponding to passed keys will be set.
     *
     * @param array $data Associative array with [attribute => value] pairs
     * @return self
     * @throws \InvalidArgumentException
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
                case 'numberFormat' :
                    $this->setNumberFormat($v);
                    break;
                case 'unitSeparator' :
                    $this->setUnitSeparator($v);
                    break;
                default :
                    throw new \InvalidArgumentException("Unknown memory-size formatter option \"{$k}\"");
            }
        }
        return $this;
    }

    /**
     * Returns currently defined standard or null if undefined
     *
     * @return StandardInterface|null
     */
    public function getStandard(): ?StandardInterface
    {
        return $this->standard;
    }

    /**
     * Sets standard to use in formatter
     *
     * @param StandardInterface $standard
     * @return FormatterOptions
     */
    public function setStandard(StandardInterface $standard): FormatterOptions
    {
        $this->standard = $standard;
        return $this;
    }

    /**
     * Returns currently defined minimum amount of decimals (default is: 0)
     *
     * @return int
     */
    public function getMinDecimals(): int
    {
        return $this->minDecimals;
    }

    /**
     * Sets minimum amount of decimals
     *
     * @param int $minDecimals
     * @return FormatterOptions
     */
    public function setMinDecimals(int $minDecimals): FormatterOptions
    {
        $this->minDecimals = $minDecimals;
        return $this;
    }

    /**
     * Returns currently defined maximal amount of decimals (default is: 2)
     *
     * @return int
     */
    public function getMaxDecimals(): int
    {
        return $this->maxDecimals;
    }

    /**
     * Sets maximal amount of decimals
     *
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
     * Whether number format is defined or not
     *
     * @return bool
     */
    public function hasNumberFormat(): bool
    {
        return $this->numberFormat !== null;
    }

    /**
     * Returns currently defined number format or null if undefined
     *
     * @return null|NumberFormat
     */
    public function getNumberFormat(): ?NumberFormat
    {
        return $this->numberFormat;
    }

    /**
     * Set number format definition
     *
     * @param NumberFormat|array $numberFormat
     * @return FormatterOptions
     * @throws \InvalidArgumentException
     */
    public function setNumberFormat($numberFormat): FormatterOptions
    {
        if ($numberFormat instanceof NumberFormat) {
            $this->numberFormat = $numberFormat;
        } elseif (is_array($numberFormat)) {
            $this->numberFormat = NumberFormat::fromArray($numberFormat);
        } else {
            throw new \InvalidArgumentException('Invalid argument type in ' . __METHOD__);
        }
        return $this;
    }

    /**
     * Returns currently defined separator between number and measurement unit (default is " ")
     *
     * @return string
     */
    public function getUnitSeparator(): string
    {
        return $this->unitSeparator;
    }

    /**
     * Sets separator between number and measurement unit
     *
     * @param string $unitSeparator
     * @return FormatterOptions
     */
    public function setUnitSeparator(string $unitSeparator): FormatterOptions
    {
        $this->unitSeparator = $unitSeparator;
        return $this;
    }
}
