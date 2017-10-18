<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testIecStandard()
    {
        // IEC standard is default, so we don't need to pass it as option
        $formatter = new \gugglegum\MemorySize\Formatter();

        $this->assertEquals('64 KiB', $formatter->format(65536));
        $this->assertEquals('512 B', $formatter->format(512));
        $this->assertEquals('1.5 KiB', $formatter->format(1536));
        $this->assertEquals('1.21 KiB', $formatter->format(1234));
        $this->assertEquals('976.56 KiB', $formatter->format(1000000));
        $this->assertEquals('1 MiB', $formatter->format(1048576));
        $this->assertEquals('4.38 GiB', $formatter->format(4707319808)); // DVD-R(W)
        $this->assertEquals('4.38 GiB', $formatter->format(4700372992)); // DVD+R(W)
    }

    public function testJedecStandard()
    {
        $formatter = new \gugglegum\MemorySize\Formatter([
            'standard' => new \gugglegum\MemorySize\Standards\JEDEC(),
        ]);

        $this->assertEquals('64 KB', $formatter->format(65536));
        $this->assertEquals('512 B', $formatter->format(512));
        $this->assertEquals('1.5 KB', $formatter->format(1536));
        $this->assertEquals('1.21 KB', $formatter->format(1234));
        $this->assertEquals('976.56 KB', $formatter->format(1000000));
        $this->assertEquals('1 MB', $formatter->format(1048576));
        $this->assertEquals('4.38 GB', $formatter->format(4707319808)); // DVD-R(W)
        $this->assertEquals('4.38 GB', $formatter->format(4700372992)); // DVD+R(W)
    }

    public function testMaxDecimals()
    {
        $formatter = new \gugglegum\MemorySize\Formatter([
            'maxDecimals' => 3,
        ]);
        // test maxDecimals passed through constructor
        $this->assertEquals('4.384 GiB', $formatter->format(4707319808)); // DVD-R(W)

        // test maxDecimals passed through setOptions
        $formatter->setOptions(['maxDecimals' => 1]);
        $this->assertEquals('4.4 GiB', $formatter->format(4700372992, ['maxDecimals' => 1])); // DVD+R(W)

        // test maxDecimals passed through getOptions()->setMaxDeciamls()
        $formatter->getOptions()->setMaxDecimals(3);
        $this->assertEquals('4.384 GiB', $formatter->format(4707319808)); // DVD-R(W)

        // test maxDecimals passed through override options
        $this->assertEquals('4.4 GiB', $formatter->format(4700372992, ['maxDecimals' => 1])); // DVD+R(W)
    }

    public function testMinDecimals()
    {
        $formatter = new \gugglegum\MemorySize\Formatter();
        $this->assertEquals('1.0 MiB', $formatter->format(1048576, ['minDecimals' => 1])); // DVD-R(W)
        $this->assertEquals('1.50 GiB', $formatter->format(1610612736, ['minDecimals' => 2])); // DVD+R(W)
    }

    public function testFixedDecimals()
    {
        $formatter = new \gugglegum\MemorySize\Formatter();
        $this->assertEquals('1.000 MiB', $formatter->format(1048576, ['fixedDecimals' => 3])); // DVD-R(W)
        $this->assertEquals('2 GiB', $formatter->format(1610612736, ['fixedDecimals' => 0])); // DVD+R(W)
    }

    public function testDecimalPoint()
    {
        $formatter = new \gugglegum\MemorySize\Formatter(['decimalPoint' => ',']);
        $this->assertEquals('1,21 KiB', $formatter->format(1234));
    }

    public function testThousandsSeparator()
    {
        // We use JEDEC standard to test thousands separator because JEDEC supports only GB as maximum unit. IEC
        // supports TiB, PiB, etc.
        $formatter = new \gugglegum\MemorySize\Formatter([
            'standard' => new \gugglegum\MemorySize\Standards\JEDEC(),
        ]);
        // No thousands separator -- default
        $this->assertEquals('4384.03 GB', $formatter->format(4707319808000)); // 1000 x DVD-R(W)

        // With space (" ") thousands separator using setOptions()
        $formatter->setOptions(['thousandsSeparator' => ' ']);
        $this->assertEquals('4 384.03 GB', $formatter->format(4707319808000)); // 1000 x DVD-R(W)

        // With comma (",") thousands separator using override options
        $this->assertEquals('4,384.03 GB', $formatter->format(4707319808000, ['thousandsSeparator' => ','])); // 1000 x DVD-R(W)
    }

    public function testUnitSeparator()
    {
        $formatter = new \gugglegum\MemorySize\Formatter(['unitSeparator' => '']);
        $this->assertEquals('1.21KiB', $formatter->format(1234));
        $formatter->getOptions()->setUnitSeparator(' ');
        $this->assertEquals('1.21 KiB', $formatter->format(1234));
        $this->assertEquals('1.21_KiB', $formatter->format(1234, ['unitSeparator' => '_']));
    }

    public function testNegative()
    {
        $formatter = new \gugglegum\MemorySize\Formatter();
        $this->assertEquals('-1.21 KiB', $formatter->format(-1234));
        $this->assertEquals('-4.28 TiB', $formatter->format(-4707319808000)); // -1000 x DVD-R(W)
    }

    public function testSetOptions()
    {
        // Set all options through constructor (setFromArray) to non-default values
        $formatter = new \gugglegum\MemorySize\Formatter([
            'standard' => new \gugglegum\MemorySize\Standards\JEDEC(),
            'minDecimals' => 2,
            'maxDecimals' => 4,
            'decimalPoint' => ',',
            'thousandsSeparator' => ' ',
            'unitSeparator' => "\t",
        ]);

        // Check all options set correctly via getters
        $this->assertInstanceOf(\gugglegum\MemorySize\Standards\JEDEC::class, $formatter->getOptions()->getStandard());
        $this->assertEquals(2, $formatter->getOptions()->getMinDecimals());
        $this->assertEquals(4, $formatter->getOptions()->getMaxDecimals());
        $this->assertEquals(',', $formatter->getOptions()->getDecimalPoint());
        $this->assertEquals(' ', $formatter->getOptions()->getThousandsSeparator());
        $this->assertEquals("\t", $formatter->getOptions()->getUnitSeparator());

        // Change all previously defined options via setter methods on options instance
        $formatter->getOptions()
            ->setStandard(new \gugglegum\MemorySize\Standards\IEC())
            ->setMinDecimals(0)
            ->setMaxDecimals(2)
            ->setDecimalPoint('.')
            ->setThousandsSeparator(',')
            ->setUnitSeparator(' ');

        // Check once again that all set correctly
        $this->assertInstanceOf(\gugglegum\MemorySize\Standards\IEC::class, $formatter->getOptions()->getStandard());
        $this->assertEquals(0, $formatter->getOptions()->getMinDecimals());
        $this->assertEquals(2, $formatter->getOptions()->getMaxDecimals());
        $this->assertEquals('.', $formatter->getOptions()->getDecimalPoint());
        $this->assertEquals(',', $formatter->getOptions()->getThousandsSeparator());
        $this->assertEquals(' ', $formatter->getOptions()->getUnitSeparator());
    }

    public function testExceptionOnUnknownOption1()
    {
        $this->expectException(\gugglegum\MemorySize\Exception::class);
        $this->expectExceptionMessage('Unknown memory-size formatter options "unknownOption"');
        new \gugglegum\MemorySize\Formatter(['unknownOption' => 'value']);
    }

    public function testExceptionOnUnknownOption2()
    {
        $this->expectException(\gugglegum\MemorySize\Exception::class);
        $this->expectExceptionMessage('Unknown memory-size formatter options "unknownOption"');
        $formatter = new \gugglegum\MemorySize\Formatter();
        $formatter->setOptions(['unknownOption' => 'value']);
    }

}
