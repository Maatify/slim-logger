<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-04-18
 * Time: 13:02
 * Project: slim-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);


namespace Maatify\SlimLogger\Tests\Log;

use PHPUnit\Framework\TestCase;
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Store\File\Path;
use Psr\Http\Message\ServerRequestInterface;

class LoggerTest extends TestCase
{
    private string $logDir;

    protected function setUp(): void
    {
        // Important: logs directory ends up here due to Path logic
        $this->logDir = __DIR__ . '/temp_logs/logs';
        if (! is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory(dirname($this->logDir)); // delete full temp_logs
    }

    public function testLogsInfoMessageWithoutRequest()
    {
        $logger = new Logger(new Path(dirname($this->logDir)));
        $logger->record('Unit test info message', null, 'test/action', 'info');

        $expectedDir = $this->logDir . '/' . date('y/m/d') . '/test';
        $files = glob($expectedDir . '/*_info_*.log');

        $this->assertNotEmpty($files, 'No log file found for info level.');
        $filePath = $files[0];

        $this->assertFileExists($filePath);
        $json = json_decode(file_get_contents($filePath), true);

        $this->assertEquals('info', $json['level']);
        $this->assertEquals('Unit test info message', $json['log_details']['message']);
    }

    public function testLogsWithMockedRequest()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getUri')->willReturn('http://test.local/api');
        $request->method('getHeaders')->willReturn(['User-Agent' => ['PHPUnit']]);
        $request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '127.0.0.1']);
        $request->method('getHeaderLine')->willReturn('PHPUnit');

        $logger = new Logger(new Path(dirname($this->logDir)));
        $logger->record(['event' => 'test'], $request, 'test/request', 'debug');

        $expectedDir = $this->logDir . '/' . date('y/m/d') . '/test';
        $files = glob($expectedDir . '/*_debug_*.log');

        $this->assertNotEmpty($files, 'No debug log file generated.');
        $filePath = $files[0];

        $this->assertFileExists($filePath);
        $json = json_decode(file_get_contents($filePath), true);

        $this->assertEquals('debug', $json['level']);
        $this->assertEquals('POST', $json['request_info']['method']);
        $this->assertEquals('PHPUnit', $json['request_info']['user_agent']);
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = "$dir/$item";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}