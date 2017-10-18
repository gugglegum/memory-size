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
     * @var StandardInterface[]|null
     */
    private $standards;

    /**
     * @var bool
     */
    private $allowNegative = true;

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
                case 'standards' :
                    $this->setStandards($v);
                    break;
                case 'allowNegative' :
                    $this->setAllowNegative($v);
                    break;
                default :
                    throw new Exception("Unknown memory-size parser options \"{$k}\"");
            }
        }
        return $this;
    }

    /**
     * @return StandardInterface[]|null
     */
    public function getStandards()
    {
        return $this->standards;
    }

    /**
     * @param StandardInterface[] $standards
     * @return ParserOptions
     * @throws Exception
     */
    public function setStandards(array $standards): ParserOptions
    {
        foreach ($standards as $standard) {
            if (!$standard instanceof StandardInterface) {
                throw new Exception('Passed information measurement standard object ' . get_class($standard) . ' not implements ' . StandardInterface::class);
            }
        }
        $this->standards = $standards;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowNegative(): bool
    {
        return $this->allowNegative;
    }

    /**
     * @param bool $allowNegative
     * @return ParserOptions
     */
    public function setAllowNegative(bool $allowNegative): ParserOptions
    {
        $this->allowNegative = $allowNegative;
        return $this;
    }
}
