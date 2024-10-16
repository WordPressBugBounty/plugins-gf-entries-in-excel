<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
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
 */
class Version extends Enum
{
    const STORE = 0x000A; // 1.00
    const DEFLATE = 0x0014; // 2.00
    const ZIP64 = 0x002D; // 4.50
}
