<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-04-18
 * Time: 13:01
 * Project: slim-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\SlimLogger\Tests\Store\File;

use PHPUnit\Framework\TestCase;
use Maatify\SlimLogger\Store\File\Path;

class PathTest extends TestCase
{
    public function testGetLogRootPathIsValid()
    {
        $baseDir = __DIR__ . '/../Log/temp_logs';
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $path = new Path($baseDir);
        $logPath = $path->getLogRootPath();

        $this->assertStringEndsWith('/logs', $logPath);
        $this->assertStringStartsWith($baseDir, $logPath);
        $this->assertDirectoryExists(dirname($logPath));
    }

}