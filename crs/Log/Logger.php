<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     slim-logger
 * @Project     slim-logger
 * @author      Mohamed Abdulalim (megyptm)
 * @link        https://github.com/Maatify/slim-logger
 * @since       2025-04-17 4:07 PM
 * @see         https://www.maatify.dev
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

namespace Maatify\SlimLogger\Log;

use Maatify\SlimLogger\Store\File\Path;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;

class Logger
{
    private static Logger $instance;
    private string $logRoot;
    private string $extension;

    public static function setInstance(self $logger): void
    {
        self::$instance = $logger;
    }

    public function __construct(Path $path, string $extension = 'log')
    {
        $this->logRoot = $path->getLogRootPath();
        $this->extension = $extension;
    }

    public function record(
        Throwable|string|array $message,
        ?ServerRequestInterface $request = null,
        string $logFile = 'app',
        LogLevelEnum $level = LogLevelEnum::Info
    ): void {
        $levelValue = strtolower($level->value);
        $messageArray = [];

        if ($message instanceof Throwable) {
            $messageArray['log_details'] = [
                'error_message' => $message->getMessage(),
                'file' => $message->getFile(),
                'line' => $message->getLine(),
                'code' => $message->getCode(),
                'trace' => $message->getTraceAsString(),
            ];
        } elseif (is_array($message)) {
            $messageArray['log_details'] = $message;
        } else {
            $messageArray['log_details'] = ['message' => $message];
        }

        $messageArray['level'] = $levelValue;
        $messageArray['logger_info'] = [
            'timestamp' => time(),
            'time' => date("Y-m-d H:i:s"),
        ];

        if ($request) {
            $messageArray['request_info'] = [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'headers' => $request->getHeaders(),
                'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? '',
                'user_agent' => $request->getHeaderLine('User-Agent'),
            ];
        }

        $json = json_encode($messageArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $targetDir = $this->createFolderByDate($logFile);
        $filename = $targetDir . '/'
                    . $this->sanitizeFilename($logFile)
                    . '_response_' . $levelValue . '_' . date("YmdA") . '.' . $this->extension;

        file_put_contents($filename, $json . PHP_EOL, FILE_APPEND);
    }

    public static function recordStatic(
        Throwable|string|array $message,
        ?ServerRequestInterface $request = null,
        string $logFile = 'app',
        LogLevelEnum $level = LogLevelEnum::Info,
        string $extension = 'log'
    ): void {
        if (!isset(self::$instance)) {
            $basePath = dirname(__DIR__, 3); // Default root for logs
            self::$instance = new self(new \Maatify\SlimLogger\Store\File\Path($basePath), $extension);
        }

        self::$instance->record($message, $request, $logFile, $level);
    }

    public function getLogFilePath(string $action, LogLevelEnum $level = LogLevelEnum::Info, string $subFolder = 'app'): string
    {
        $levelValue = strtolower($level->value);
        return $this->logRoot . '/' . date('y/m/d') . '/' . $subFolder . '/'
               . $this->sanitizeFilename($action)
               . '_response_' . $levelValue . '_' . date("Y-m-d-A") . '.' . $this->extension;
    }

    private function createFolderByDate(string $logFile): string
    {
        $path = $this->logRoot;
        $folders = [date('y'), date('m'), date('d')];

        if (str_contains($logFile, '/')) {
            $parts = explode('/', $logFile);
            array_pop($parts); // remove filename
            $folders = array_merge($folders, $parts);
        }

        foreach ($folders as $folder) {
            $path .= '/' . $folder;
            $this->ensureFolder($path);
        }

        return $path;
    }

    private function ensureFolder(string $dir): void
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            file_put_contents($dir . '/index.php', "<?php\nheader('Location: /404');");
        }
    }

    private function sanitizeFilename(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
    }
}
