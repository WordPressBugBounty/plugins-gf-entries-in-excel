<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Container;

use GFExcel\Vendor\League\Container\ServiceProvider\BootableServiceProviderInterface;
use GFExcel\Vendor\League\Container\ServiceProvider\ServiceProviderInterface as LeagueServiceProviderInterface;

interface ServiceProviderInterface extends LeagueServiceProviderInterface, BootableServiceProviderInterface
{
}
