<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace GFExcel\Vendor\ZipStream\Option;

use GFExcel\Vendor\MyCLabs\Enum\Enum;

/**
 * Class Version
 * @package GFExcel\Vendor\ZipStream\Option
 *
 * @method static STORE(): Version
 * @method static DEFLATE(): Version
 * @method static ZIP64(): Version
 * @psalm-immutable
 * @psalm-template int
 * @extends Enum<int>
 */
class Version extends Enum
{
    public const STORE = 0x000A; // 1.00

    public const DEFLATE = 0x0014; // 2.00

    public const ZIP64 = 0x002D; // 4.50
}
