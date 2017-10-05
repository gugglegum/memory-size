<?php

declare(strict_types=1);

namespace gugglegum\MemorySize;

use gugglegum\AbstractEntity\AbstractEntity;
use gugglegum\MemorySize\Standards\StandardInterface;

/**
 * Parser options
 *
 * @package gugglegum\MemorySize
 */
class Options extends AbstractEntity
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
     * @return StandardInterface[]|null
     */
    public function getStandards()
    {
        return $this->standards;
    }

    /**
     * @param StandardInterface[] $standards
     * @return Options
     * @throws Exception
     */
    public function setStandards(array $standards): Options
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
     * @return Options
     */
    public function setAllowNegative(bool $allowNegative): Options
    {
        $this->allowNegative = $allowNegative;
        return $this;
    }
}
