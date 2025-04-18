<?php

declare(strict_types=1);

namespace Maatify\SlimLogger\Tests\Log;

use PHPUnit\Framework\TestCase;
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Log\LogLevelEnum;
use Maatify\SlimLogger\Store\File\Path;
use Psr\Http\Message\ServerRequestInterface;

class LoggerTest extends TestCase
{
    private string $logDir;

    protected function setUp(): void
    {
        $this->logDir = __DIR__ . '/temp_logs/logs';
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory(dirname($this->logDir));
    }

    public function testLogsInfoMessageWithoutRequest(): void
    {
        $logger = new Logger(new Path(dirname($this->logDir)));
        $logger->record('Unit test info message', null, 'test/action', LogLevelEnum::Info);

        $expectedDir = $this->logDir . '/' . date('y/m/d') . '/test';
        $files = glob($expectedDir . '/*_info_*.log');

        $this->assertNotEmpty($files, 'No log file found for info level.');
        $filePath = $files[0];

        $this->assertFileExists($filePath);
        $json = json_decode(file_get_contents($filePath), true);

        $this->assertSame('info', $json['level']);
        $this->assertSame('Unit test info message', $json['log_details']['message']);
    }

    public function testLogsWithMockedRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getUri')->willReturn('http://test.local/api');
        $request->method('getHeaders')->willReturn(['User-Agent' => ['PHPUnit']]);
        $request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '127.0.0.1']);
        $request->method('getHeaderLine')->willReturn('PHPUnit');

        $logger = new Logger(new Path(dirname($this->logDir)));
        $logger->record(['event' => 'test'], $request, 'test/request', LogLevelEnum::Debug);

        $expectedDir = $this->logDir . '/' . date('y/m/d') . '/test';
        $files = glob($expectedDir . '/*_debug_*.log');

        $this->assertNotEmpty($files, 'No debug log file generated.');
        $filePath = $files[0];

        $this->assertFileExists($filePath);
        $json = json_decode(file_get_contents($filePath), true);

        $this->assertSame('debug', $json['level']);
        $this->assertSame('POST', $json['request_info']['method']);
        $this->assertSame('PHPUnit', $json['request_info']['user_agent']);
    }

    public function testStaticLoggerWithEnum(): void
    {
        $logger = new Logger(new Path(dirname($this->logDir)));
        Logger::setInstance($logger); // Inject test path

        Logger::recordStatic(
            'Logged using static with enum',
            null,
            'static/test/path',
            LogLevelEnum::Warning
        );

        $expectedDir = $this->logDir . '/' . date('y/m/d') . '/static/test';
        $files = glob($expectedDir . '/*_warning_*.log');

        $this->assertNotEmpty($files, 'No warning log file found from static.');
        $filePath = $files[0];

        $json = json_decode(file_get_contents($filePath), true);
        $this->assertSame('warning', $json['level']);
    }


    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;

        foreach (array_diff(scandir($dir), ['.', '..']) as $item) {
            $path = "$dir/$item";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}
