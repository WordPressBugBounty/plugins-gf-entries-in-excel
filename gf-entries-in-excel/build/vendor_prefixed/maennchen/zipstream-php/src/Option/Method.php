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
 * Methods enum
 *
 * @method static STORE(): Method
 * @method static DEFLATE(): Method
 * @psalm-immutable
 * @psalm-template int
 * @extends Enum<int>
 */
class Method extends Enum
{
    public const STORE = 0x00;

    public const DEFLATE = 0x08;
}
