<?php
declare(strict_types=1);

namespace gugglegum\MemorySize;

/**
 * Number Format
 *
 * Represents a type of value used in ParserOptions and FormatterOptions.
 *
 * @package gugglegum\MemorySize
 */
class NumberFormat
{
    const DEFAULT_DECIMAL_POINT = '.';
    const DEFAULT_THOUSANDS_SEPARATOR = '';

    /**
     * Decimal point that splits integer and fractional parts
     *
     * @var string
     */
    private $decimalPoint = self::DEFAULT_DECIMAL_POINT;

    /**
     * Separator for thousands, millions, billions, trillions, etc.
     *
     * @var string
     */
    private $thousandsSeparator = self::DEFAULT_THOUSANDS_SEPARATOR;

    /**
     * NumberFormat constructor
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        $this->setFromArray($data);
    }

    /**
     * Create instance with default values
     *
     * @return NumberFormat
     */
    public static function createDefault(): NumberFormat
    {
        return self::create(self::DEFAULT_DECIMAL_POINT);
    }

    /**
     * Create instance from arguments
     *
     * @param string $decimalPoint
     * @param string $thousandsSeparator
     * @return NumberFormat
     */
    public static function create(string $decimalPoint, string $thousandsSeparator = self::DEFAULT_THOUSANDS_SEPARATOR): NumberFormat
    {
        return (new self())
            ->setDecimalPoint($decimalPoint)
            ->setThousandsSeparator($thousandsSeparator);
    }

    /**
     * Create instance from array
     *
     * @param array $data
     * @return NumberFormat
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        return (new self($data));
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
                case 'decimalPoint' :
                    $this->setDecimalPoint($v);
                    break;
                case 'thousandsSeparator' :
                    $this->setThousandsSeparator($v);
                    break;
                default :
                    throw new \InvalidArgumentException("Unknown number format option \"{$k}\"");
            }
        }
        return $this;
    }

    /**
     * Returns currently defined decimal point separator (default is: ".")
     *
     * @return string
     */
    public function getDecimalPoint(): string
    {
        return $this->decimalPoint;
    }

    /**
     * Sets decimal point separator
     *
     * @param string $decimalPoint
     * @return NumberFormat
     */
    public function setDecimalPoint(string $decimalPoint): NumberFormat
    {
        $this->decimalPoint = $decimalPoint;
        return $this;
    }

    /**
     * Returns currently defined thousands separator (default is: "")
     *
     * @return string
     */
    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator;
    }

    /**
     * Sets thousands separator
     *
     * @param string $thousandsSeparator
     * @return NumberFormat
     */
    public function setThousandsSeparator(string $thousandsSeparator): NumberFormat
    {
        $this->thousandsSeparator = $thousandsSeparator;
        return $this;
    }
}
