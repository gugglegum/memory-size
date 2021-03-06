# Memory Size

This is easy to use Composer package to deal with human-friendly formatted sizes of memory blocks or files (like "32 KB", "4.87 MiB" and so on). It consist of 2 parts: Parser and Formatter. The parser allows to parse formatted sizes in human-friendly view. Whereas the formatter allows to format size to human-friendly view. They are compatible each other, so parser able to parse string generated by formatter and vise-versa. This package was made with a focus on international standards and you may add your own standard implementation. Generally here's two main standards available out-of-the-box:

1. JEDEC Standard 100B.01 (JESD100B.01) (https://en.wikipedia.org/wiki/JEDEC_memory_standards)
2. ISO/IEC 80000 (https://en.wikipedia.org/wiki/ISO/IEC_80000)  

The JESD100B.01 describes old-style measure units like "KB", "MB" & "GB" which are means 1024 bytes, 1024^2 bytes & 1024^3 bytes accordingly. Although these measure units are well-known and popular they are deprecated because they collide with SI (metric) prefixes where "M" means 1000^2 (million) and "G" means 1000^3 (billion). By the way, with "Kilo" prefix there's no collision because SI-prefix for "Kilo" defined as "k" (lowercase). Also JESD100B.01 allows to use shortened records contains only prefix, i.e. "K", "M" and "G". These records
are little confusing but not so inconsistent as "KB", "MB" and "GB". Note that JESD100B.01 defines only kilobytes, megabytes and gigabytes. It's not defined terabytes, petabytes and so on. So "TB" unit should not be treated as 1024^4. If you need a standard which defines also "TB", "PB", etc. you may write your own standard implementation and set it into parser or formatter. Or you may write me and offer your own elegant solution of this problem. 

The ISO/IEC 80000 is more modern standard. It solves problem of inconsistency of memory prefixes and SI-prefixes by introducing new binary prefixes especially for degrees of 2: "KiB", "MiB", "GiB", "TiB", "PiB", etc. which means 1024, 1024^2, 1024^3, 1024^4, 1024^5, etc. But this standard also refers to SI-prefixes. So, "1 MB" means 1000000 (1000^2) bytes, "1 GB" means 1000000000 (1000^3) bytes and so on.

These two standards (JESD100B.01 and ISO/IEC 80000) are implemented as separated classes with standard interface. You may define which standard and in which order to use or you can make your own standard implementation and pass your standard instance to the parser or to the formatter. 
 
## Parser

For example, you want to accept from a user the size of anything in bytes (e.g. via config file or command line arguments). Of course, you can force a user to specify the exact size in bytes. But this is not very convenient when it comes to gigabytes and terabytes. Or maybe you need to parse data that already contains formatted file sizes like "700M" or "4.38GB". You may use this parser for this. Here's an example:

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

$parser = new \gugglegum\MemorySize\Parser();
var_dump($parser->parse('700M'));

```

Will produce output:

```
int(734003200)
```

Parser uses standard implementations to parse formatted size. It can use several standards. At first it tries to parse measure unit by first standard. If it doesn't know this unit, it tries second and so on. By default parser uses the ISO/IEC 80000 as first (primary) and the JESD100B.01 as second (secondary). So it will parse both "32K" form and "32 KiB" correctly. But if you need to treat "8 MB" or "2 GB" as binary prefixes, you need to remove ISO/IEC 80000 standard or to make JESD100B.01 first (primary).

This is an example how to make JESD100B.01 standard primary and ISO/IEC 80000 standard secondary:

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

$parser = new \gugglegum\MemorySize\Parser([
    'standards' => [
        new \gugglegum\MemorySize\Standards\JEDEC(),
        new \gugglegum\MemorySize\Standards\IEC(),
    ],
]);

var_dump($parser->parse('32 kB'));
var_dump($parser->parse('32 KB'));
var_dump($parser->parse('32 KiB'));

var_dump($parser->parse('8 MB'));
var_dump($parser->parse('8 MiB'));

```

Will produce output:

```
int(32000)
int(32768)
int(32768)
int(8388608)
int(8388608)
```

You may see here that "32 kB" parsed as 32000 because lowercase "k" prefix is only SI-prefix. Binary kilo prefix is uppercase "K". This is why "32 KB" parsed as 32768 and "32 kB" parsed as 32000. "32 KiB" always parsed by ISO/IEC 80000 as 32768 because "Ki" is a special binary prefix called "kibi". "8 MB" parses as 8388608 because JEDEC is primary and it treats this prefix as binary. "8 MiB" always parses by ISO/IEC 80000 as 8388608 because "Mi" is a special binary prefix called "mebi".

### Parser Options

The default parser options are suitable for 95% of cases, so you may instantiate the parser without any options like in the first example. In the remaining 5% of cases you may pass following options as associative array in the parser constructor or use setter methods. You may pass only options you want to change from default.

 * `standards` — array of 1 or several objects implementing `\gugglegum\MemorySize\Standards\StandardInterface`.
 * `numberFormats` — array of 1 or several objects of class `\gugglegum\MemorySize\NumberFormat` or associative arrays with keys "decimalPoint" and "thousandsSeparator".
 * `allowNegative` — boolean value representing is negative values allowed or not. If not allowed the string containing negative memory size value will be failed to parse.
 
#### Initialization examples
 
Using associative array passed to the constructor:
 
```
$parser = new \gugglegum\MemorySize\Parser([
    'standards' => [
        new \gugglegum\MemorySize\Standards\JEDEC(),
        new \gugglegum\MemorySize\Standards\IEC(),
    ],
    'numberFormats' => [
        \gugglegum\MemorySize\NumberFormat::create(',', ' '),
        \gugglegum\MemorySize\NumberFormat::create('.', ','),
    ],
    'allowNegative' => false,
]);
```

The same but little different:

```
$parser = new \gugglegum\MemorySize\Parser([
    'standards' => [
        new \gugglegum\MemorySize\Standards\JEDEC(),
        new \gugglegum\MemorySize\Standards\IEC(),
    ],
    'numberFormats' => [
        [
            'decimalPoint' => ',',
            'thousandsSeparator' =>  ' ',
        ],
        new \gugglegum\MemorySize\NumberFormat([
            'decimalPoint' => ',',
            'thousandsSeparator' =>  ' ',
        ])
    ],
    'allowNegative' => false,
]);
```

Yet another variant of the same using setters:

```
$parser = new \gugglegum\MemorySize\Parser();
$parser->getOptions()->setStandards([
        new \gugglegum\MemorySize\Standards\JEDEC(),
        new \gugglegum\MemorySize\Standards\IEC(),
    ])->setNumberFormats([
        \gugglegum\MemorySize\NumberFormat::create(',', ' '),
        \gugglegum\MemorySize\NumberFormat::create('.', ','),
    ])->setAllowNegative(false);
```

## Formatter

As opposed to the parser you may need a formatter to format memory sizes or file sizes in human-friendly view. The formatter uses standard objects too. But unlike the parser the formatter may use only one standard at once. By default formatter uses ISO/IEC 80000 standard and uses only binary prefixes ("KiB", "MiB", "GiB", etc). Here's example how to use formatter:

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

$formatter = new \gugglegum\MemorySize\Formatter();
var_dump($formatter->format(32768));
var_dump($formatter->format(1536));
var_dump($formatter->format(1000000));
var_dump($formatter->format(1048576));
```

Will produce output:

```
string(6) "32 KiB"
string(7) "1.5 KiB"
string(10) "976.56 KiB"
string(5) "1 MiB"
```

If you need old-style format from JESD100B.01 you may set to use it instead of ISO/IEC 80000. Here's an example how to do this:

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

$formatter = new \gugglegum\MemorySize\Formatter([
    'standard' => new \gugglegum\MemorySize\Standards\JEDEC(),
]);
var_dump($formatter->format(32768));
var_dump($formatter->format(1536));
var_dump($formatter->format(1000000));
var_dump($formatter->format(1048576));
```

Will produce output:

```
string(5) "32 KB"
string(6) "1.5 KB"
string(9) "976.56 KB"
string(4) "1 MB"
```

### Formatter Options

The default formatter options are good enough for most cases but you may customize them if needed. You may pass following options as associative array in the formatter constructor or use setter methods. You may pass only options you want to change from default.

 * `standard` — an object implementing `\gugglegum\MemorySize\Standards\StandardInterface`. Unlike Parser, Formatter uses only one standard to format value.
 * `minDecimals` — minimum amount of decimals (integer).
 * `maxDecimals` — maximum amount of decimals (integer).
 * `fixedDecimals` — minimum and maximum amount of decimals together (integer).
 * `numberFormat` — an objects of class `\gugglegum\MemorySize\NumberFormat` or associative array with keys "decimalPoint" and "thousandsSeparator".
 * `unitSeparator` — a separator between number and measurement unit, usually space character or empty string.

#### Initialization examples
 
Using associative array passed to the constructor:
 
```
$formatter = new \gugglegum\MemorySize\Formatter([
    'standard' => new \gugglegum\MemorySize\Standards\IEC(),
    'minDecimals' => 1,
    'maxDecimals' => 3,
    'numberFormat' => \gugglegum\MemorySize\NumberFormat::create(',', ' '),
    'unitSeparator' => ' ',
]);
```

Th same but little different variant:

```
$formatter = new \gugglegum\MemorySize\Formatter([
    'standard' => new \gugglegum\MemorySize\Standards\IEC(),
    'minDecimals' => 1,
    'maxDecimals' => 3,
    'numberFormat' => [
        'decimalPoint' => ',',
        'thousandsSeparator' =>  ' ',
    ],
    'unitSeparator' => ' ',
]);
```

## Creating your own standard implementation

As mentioned above, you able to create your own standard implementation to parse and format memory sizes as you want. You need to create a class which implements `\gugglegum\MemorySize\Standards\StandardInterface` which defines following methods:

```
    /**
     * Resolves unit of measure into multiplier. Return FALSE if unable to resolve. This method is used only in the Parser.
     *
     * @param string        $unit
     * @return float|int|false
     */
    public function unitToMultiplier(string $unit);

    /**
     * Returns associative array of measurement units where keys are units and values are multipliers corresponding to
     * the units. For example: [ 'B' => 1, 'KiB' => 1024, 'MiB' => 1048576, ... ] This method is used only in the Formatter,
     * only these measurement units will be used in formatted memory size.
     *
     * @return array
     */
    public function getByteUnitMultipliers(): array;
```

Say you need a standard that defines memory size record in bits, Kbits and Mbits. It may looks like so: 

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

class BitSizeStandard implements \gugglegum\MemorySize\Standards\StandardInterface
{
    /**
     * Resolves unit of measure into multiplier (this method is used only in Parser)
     *
     * @param string        $unit
     * @return float|int|false
     */
    public function unitToMultiplier(string $unit)
    {
        switch ($unit) {
            case 'bit' :
                return 1/8;
            case 'Kbit' :
            case 'kbit' :
                return 1/8 * 1024;
            case 'Mbit' :
            case 'mbit' :
                return 1/8 * 1024 * 1024;
            default :
                return false;
        }
    }

    /**
     * Returns associative array of measurement units where keys are units and values are multipliers corresponding to
     * the units. For example: [ 'B' => 1, 'KiB' => 1024, 'MiB' => 1048576, ... ] (this method is used only in Formatter)
     *
     * @return array
     */
    public function getByteUnitMultipliers(): array
    {
        return [
            'bit' => 1/8,
            'Kbit' => 1/8 * 1024,
            'Mbit' => 1/8 * 1024 * 1024,
        ];
    }
}

$formatter = new \gugglegum\MemorySize\Formatter([
    'standard' => new BitSizeStandard(),
]);

$formattedSize = $formatter->format(1024);
echo $formattedSize, "\n";

$parser = new \gugglegum\MemorySize\Parser([
    'standards' => [
        new BitSizeStandard(),
    ],
]);

echo $parser->parse($formattedSize), "\n";
```

You may notice that first method resolves more measurement units than second one. It allows parser to parse multiple forms of the same measurement units.

## Requirements

This package requires PHP version 7.1+.
