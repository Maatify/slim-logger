<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     slim-logger
 * @Project     slim-logger
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/slim-logger
 * @since       2025-04-18 3:07 PM
 * @see         https://www.maatify.dev
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace Maatify\SlimLogger\Log;

enum LogLevelEnum: string
{
    case Info    = 'info';
    case Debug   = 'debug';
    case Warning = 'warning';
    case Error   = 'error';
}