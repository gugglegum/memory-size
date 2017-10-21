<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testCommonFormats()
    {
        $parser = new \gugglegum\MemorySize\Parser();

        $this->assertEquals(65536, $parser->parse('65536'));
        $this->assertEquals(65536, $parser->parse('65536B'));
        $this->assertEquals(65536, $parser->parse('65536 B'));
        $this->assertEquals(8192, $parser->parse('65536b'));
        $this->assertEquals(8192, $parser->parse('65536 b'));
    }

    public function testJedecFormats()
    {
        $parser = new \gugglegum\MemorySize\Parser([
            'standards' => [
                new \gugglegum\MemorySize\Standards\JEDEC(),
                new \gugglegum\MemorySize\Standards\IEC(),
            ],
        ]);
        $this->assertEquals(65536, $parser->parse('64K'));
        $this->assertEquals(32768, $parser->parse('32KB'));
        $this->assertEquals(32768, $parser->parse('32   KB'));
        $this->assertEquals(2097152, $parser->parse('2M'));
        $this->assertEquals(1509949.44, $parser->parse('1.44 MB'));
        $this->assertEquals(4702989189.12, $parser->parse('4.38G'));
        $this->assertEquals(8589934592, $parser->parse('8GB'));
        $this->assertEquals(16384, $parser->parse('128Kbit'));
        $this->assertEquals(13107200, $parser->parse('100 Mbit'));
        $this->assertEquals(134217728, $parser->parse('1 Gbit'));
    }

    public function testIecFormats()
    {
        $parser = new \gugglegum\MemorySize\Parser();
        $this->assertEquals(64 * 1024, $parser->parse('64 KiB'));
        $this->assertEquals(32 * pow(1024, 2), $parser->parse('32MiB'));
        $this->assertEquals(32 * pow(1024, 3), $parser->parse('32 GiB'));
        $this->assertEquals(2  * pow(1024, 4), $parser->parse('2 TiB'));
        $this->assertEquals(10.5 * pow(1024, 5), $parser->parse('10.5 PiB'));
        $this->assertEquals(12 * pow(1024, 6), $parser->parse('12 EiB'));
        $this->assertEquals(34 * pow(1024, 7), $parser->parse('34 ZiB'));
        $this->assertEquals(62 * pow(1024, 8), $parser->parse('62 YiB'));
    }

    public function testSetOptions()
    {
        $parser = new \gugglegum\MemorySize\Parser();

        $this->assertEquals(32 * pow(1000, 2), $parser->parse('32MB'));
        $this->assertEquals(32 * pow(1000, 3), $parser->parse('32GB'));

        $parser->setOptions([
            'standards' => [
                new \gugglegum\MemorySize\Standards\JEDEC(),
            ],
        ]);
        $this->assertEquals(32 * pow(1024, 2), $parser->parse('32MB'));
        $this->assertEquals(32 * pow(1024, 3), $parser->parse('32GB'));
    }

    public function testOverrideOptions()
    {
        $parser = new \gugglegum\MemorySize\Parser();

        $this->assertEquals(32 * pow(1000, 2), $parser->parse('32MB'));
        $this->assertEquals(32 * pow(1000, 3), $parser->parse('32GB'));

        $overrideOptions = [
            'standards' => [
                new \gugglegum\MemorySize\Standards\JEDEC(),
            ],
        ];
        $this->assertEquals(32 * pow(1024, 2), $parser->parse('32MB', $overrideOptions));
        $this->assertEquals(32 * pow(1024, 3), $parser->parse('32GB', $overrideOptions));
    }

    public function testParseException_Negative()
    {
        $parser = new \gugglegum\MemorySize\Parser();
        $this->assertEquals(-32 * pow(1000, 2), $parser->parse('-32MB'));

        $parser->getOptions()->setAllowNegative(false);

        $this->expectException(\gugglegum\MemorySize\Exception::class);
        $this->expectExceptionMessage('Failed to parse formatted memory size');
        $parser->parse('-32MB');
    }

    public function testParseException_BadFormat1()
    {
        $parser = new \gugglegum\MemorySize\Parser();

        $this->expectException(\gugglegum\MemorySize\Exception::class);
        $this->expectExceptionMessage('Failed to parse formatted memory size');

        $parser->parse('asd MB');
    }

    public function testParseException_BadFormat2()
    {
        $parser = new \gugglegum\MemorySize\Parser();

        $this->expectException(\gugglegum\MemorySize\Exception::class);
        $this->expectExceptionMessage('Failed to recognize information measurement unit "mb"');

        $parser->parse('10 mb');
    }
}
