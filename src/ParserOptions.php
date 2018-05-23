<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Parser options
 *
 * @package gugglegum\MemorySize
 */
class ParserOptions
{
    /**
     * A list of standards used to parse
     *
     * @var StandardInterface[]|null
     */
    private $standards;

    /**
     * A list of number formats used to parse
     *
     * @var NumberFormat[]
     */
    private $numberFormats;

    /**
     * Whether negative values allowed or not
     *
     * @var bool
     */
    private $allowNegative = true;

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
        // If option "standards" not defined - set default list;
        if ($this->getStandards() === null) {
            $this->setStandards(Parser::getDefaultStandards());
        }

        // If option "numberFormat" not defined - set default
        if ($this->getNumberFormats() === null) {
            $this->setNumberFormats([ NumberFormat::createDefault() ]);
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
                case 'standards' :
                    $this->setStandards($v);
                    break;
                case 'numberFormats' :
                    $this->setNumberFormats($v);
                    break;
                case 'allowNegative' :
                    $this->setAllowNegative($v);
                    break;
                default :
                    throw new \InvalidArgumentException("Unknown memory-size parser option \"{$k}\"");
            }
        }
        return $this;
    }

    /**
     * Returns array of currently defined standards to be used in parsing, null if undefined
     *
     * @return StandardInterface[]|null
     */
    public function getStandards(): ?array
    {
        return $this->standards;
    }

    /**
     * Sets standards to be used in parsing
     *
     * @param StandardInterface[] $standards
     * @return ParserOptions
     * @throws \InvalidArgumentException
     */
    public function setStandards(array $standards): ParserOptions
    {
        if ($standards === []) {
            throw new \InvalidArgumentException('Passed empty array of standards in ' . __METHOD__);
        }
        foreach ($standards as $standard) {
            if (!$standard instanceof StandardInterface) {
                throw new \InvalidArgumentException('Passed information measurement standard object ' . get_class($standard) . ' not implements ' . StandardInterface::class);
            }
        }
        $this->standards = $standards;
        return $this;
    }

    /**
     * Returns a list of currently defined number formats, null if undefined
     *
     * @return NumberFormat[]|null
     */
    public function getNumberFormats(): ?array
    {
        return $this->numberFormats;
    }

    /**
     * Sets list of number formats allowed to be parsed
     *
     * @param NumberFormat[] $numberFormats
     * @return ParserOptions
     * @throws \InvalidArgumentException
     */
    public function setNumberFormats(array $numberFormats): ParserOptions
    {
        foreach ($numberFormats as &$numberFormat) {
            if (is_array($numberFormat)) {
                $numberFormat = new NumberFormat($numberFormat);
            } elseif (!$numberFormat instanceof NumberFormat) {
                throw new \InvalidArgumentException('Invalid number format type of ' . gettype($numberFormat) . ' passed in ' . __METHOD__);
            }
        }
        $this->numberFormats = $numberFormats;
        return $this;
    }

    /**
     * Returns is allowed negative values (if not allowed negative sizes will be failed to parse)
     *
     * @return bool
     */
    public function isAllowNegative(): bool
    {
        return $this->allowNegative;
    }

    /**
     * Sets whether allowed or not negative values
     *
     * @param bool $allowNegative
     * @return ParserOptions
     */
    public function setAllowNegative(bool $allowNegative): ParserOptions
    {
        $this->allowNegative = $allowNegative;
        return $this;
    }
}
