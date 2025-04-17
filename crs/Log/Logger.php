<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    slim-logger
 * @Project     slim-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-04-17 4:07 PM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/slim-logger  view project on GitHub
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

declare(strict_types=1);

namespace Maatify\SlimLogger\Log;

use Maatify\SlimLogger\Store\File\Path;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;

class Logger
{
    private string $logRoot;
    private string $extension;

    public function __construct(Path $path, string $extension = 'log')
    {
        $this->logRoot = $path->getLogRootPath();
        $this->extension = $extension;
    }

    public function record(Throwable|string|array $message, ServerRequestInterface $request = null, string $logFile = 'app'): void
    {
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
        $filename = $targetDir . '/' . $logFile . '_' . date("YmdH") . '.' . $this->extension;
        file_put_contents($filename, $json . PHP_EOL, FILE_APPEND);
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

    public function getLogFilePath(string $action, string $subFolder = 'post'): string
    {
        return $this->logRoot . '/' . date('y/m/d') . '/' . $subFolder . '/' . $action . '_response_' . date("Y-m-d-A") . '.' . $this->extension;
    }
}
