<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    slim-logger
 * @Project     slim-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-04-17 4:32 PM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/slim-logger  view project on GitHub
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

declare(strict_types=1);

namespace Maatify\SlimLogger\Store\File;

class Path
{
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath ?: dirname(__DIR__, 4); // Adjust to project root
    }

    public function getLogRootPath(): string
    {
        return rtrim($this->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'logs';
    }
}