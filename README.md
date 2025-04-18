# Maatify Slim Logger

---
![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---
[pkg]: <https://packagist.org/packages/maatify/slim-logger>
[pkg-stats]: <https://packagist.org/packages/maatify/slim-logger/stats>
[![Current version](https://img.shields.io/packagist/v/maatify/slim-logger)][pkg]
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/slim-logger)][pkg]
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/slim-logger)][pkg-stats]
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/slim-logger)][pkg-stats]
[![Stars](https://img.shields.io/packagist/stars/maatify/slim-logger)](https://github.com/maatify/slim-logger/stargazers)

[![Tests](https://github.com/maatify/slim-logger/actions/workflows/run-tests.yml/badge.svg)](https://github.com/maatify/slim-logger/actions)

**Maatify Slim Logger** is a lightweight, PSR-7 compatible, Slim-friendly structured logger for PHP applications. It is part of the modular [Maatify](https://maatify.dev) ecosystem.

---

## 🚀 Features

- ✅ PSR-7 request-aware logging (integrates with Slim Framework)
- ✅ Logs to file in JSON format
- ✅ Includes log levels: `info`, `debug`, `warning`, `error`
- ✅ File names include log level
- ✅ Supports static logging via `recordStatic()`
- ✅ Automatically creates secure log directories
- ✅ Date-based folder structure
- ✅ Works with Slim or pure PHP
- ✅ No external dependencies (other than `psr/http-message`)

---

## 📦 Installation

```bash
composer require maatify/slim-logger
```

Then dump autoload if needed:

```bash
composer dump-autoload
```

---

## 🧱 Namespaces

- Logger class: `Maatify\SlimLogger\Log\Logger`
- Path helper: `Maatify\SlimLogger\Store\File\Path`

---

## 📁 Folder Structure

```
maatify-slim-logger/
├── src/
│   └── Log/
│       └── Logger.php
│   └── Store/
│       └── File/
│           └── Path.php
```

---

## ✅ How It Works

`Logger` writes logs as structured, pretty JSON to disk.

Log files are saved under:

```
/logs/yy/mm/dd/<path>_response_<level>_<timestamp>.log
```

Supports both Slim apps and plain PHP.

---

## 💡 Usage for Slim Developers

### 1. Instantiate

```php
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
```

### 2. Inside a Route

```php
$app->get('/log', function ($request, $response) use ($logger) {
    $logger->record('User accessed logs.', $request, 'api/user/logs', 'info');
    return $response->withStatus(200)->write("Log saved.");
});
```

---

## 🧩 Usage for Pure PHP Developers

```php
require 'vendor/autoload.php';

use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
$logger->record('Log from plain PHP script', null, 'scripts/manual', 'debug');
```

---

## ⚠️ Exception Logging

```php
try {
    throw new \Exception('Something failed!');
} catch (\Throwable $e) {
    $logger->record($e, null, 'errors/runtime', 'error');
}
```

---

## 📣 Static Logging (recordStatic)

You can also log **without creating an object** using:

```php
Logger::recordStatic(
    Throwable|string|array $message,
    ServerRequestInterface $request = null,
    string $logFile = 'app',
    string $level = 'info',
    string $extension = 'log'
);
```

### ✅ Example

```php
use Maatify\SlimLogger\Log\Logger;

Logger::recordStatic('Maintenance mode enabled.', null, 'system/flags', 'info');
```

### ⚠️ Static Exception Log

```php
try {
    throw new \Exception("Crash on startup");
} catch (\Throwable $e) {
    Logger::recordStatic($e, null, 'boot/errors', 'error');
}
```

---

## 🔍 Log File Output

```
/logs/
└── 24/
    └── 04/
        └── 18/
            └── system_flags_response_info_20250418AM.log
```

---

## ⚙️ Optional Configuration

| Option      | Default        | Description                        |
|-------------|----------------|------------------------------------|
| `Path`      | `project/logs` | Base log directory (via `Path`)    |
| `Extension` | `.log`         | File extension                     |

---

## 🧪 Testing

Run locally:

```bash
composer test
```

### ✅ GitHub CI

See `.github/workflows/run-tests.yml`  
Every push or PR runs tests automatically using PHPUnit.

---

## 📄 License

[MIT License](./LICENSE) © 2025 [Maatify.dev](https://maatify.dev)

---

## 🙋‍♂️ Questions or Feedback?

- 🐙 GitHub: [github.com/maatify/slim-logger](https://github.com/maatify/slim-logger)


## 🚀 Features

- ✅ PSR-7 request-aware logging (integrates with Slim Framework)
- ✅ Logs to file in JSON format
- ✅ Includes log **levels** (`info`, `debug`, `warning`, `error`)
- ✅ File names include log level
- ✅ Date-based folder structure
- ✅ Automatically creates secure log directories
- ✅ Works with Slim or pure PHP
- ✅ No external dependencies (other than `psr/http-message`)

---

## 📦 Installation

Install via Composer:

```bash
composer require maatify/slim-logger
```

Then dump the autoloader if needed:

```bash
composer dump-autoload
```

---

## 🧱 Namespaces

- Logger class: `Maatify\SlimLogger\Log\Logger`
- Path helper: `Maatify\SlimLogger\Store\File\Path`

Autoloaded via PSR-4.

---

## 📁 Folder Structure

```
maatify-slim-logger/
├── src/
│   └── Log/
│       └── Logger.php
│   └── Store/
│       └── File/
│           └── Path.php
```

---

## ✅ How It Works

`Logger`:
- Writes logs as structured, pretty JSON.
- Logs are saved hourly in files named like:

```
api_user_response_error_20250417AM.log
```

- Organizes logs by date into nested folders (`/logs/yy/mm/dd`)
- Automatically includes request info if available (via PSR-7)
- Can be used in Slim or pure PHP apps

---

## 💡 Usage for Slim Developers

### 1. Instantiate Logger

```php
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
```

### 2. Use in a Route

```php
$app->get('/log', function ($request, $response) use ($logger) {
    $logger->record('User accessed log route.', $request, 'api/user/logs', 'info');
    return $response->withStatus(200)->write("Log recorded.");
});
```

---

### 📝 Example Log Structure

```json
{
  "log_details": {
    "message": "User accessed log route."
  },
  "level": "info",
  "logger_info": {
    "timestamp": 1713370000,
    "time": "2025-04-17 14:10:00"
  },
  "request_info": {
    "method": "GET",
    "uri": "http://localhost/log",
    "headers": {
      "Host": ["localhost"],
      "User-Agent": ["..."]
    },
    "ip": "127.0.0.1",
    "user_agent": "Mozilla/5.0 ..."
  }
}
```

---

## 🧩 Usage for Pure PHP Developers

You can still use the logger outside Slim — just skip the request.

### 1. Setup

```php
require 'vendor/autoload.php';

use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
```

### 2. Record Logs

```php
$logger->record('Something happened in pure PHP.', null, 'scripts/cronjob', 'warning');
```

---

## ⚠️ Logging Exceptions

```php
try {
    throw new \Exception('Oops, something failed.');
} catch (\Throwable $e) {
    $logger->record($e, null, 'api/errors', 'error');
}
```

---

## 🔍 Log File Output

Logs are saved by level and date:

```
/logs/
└── 24/
    └── 04/
        └── 17/
            └── api_user_response_error_20250417AM.log
```

---

## ⚙️ Optional Configuration

| Option      | Default        | Description                       |
|-------------|----------------|-----------------------------------|
| `Path`      | `project/logs` | Base directory for log storage    |
| `Extension` | `.log`         | Log file extension (`log`, `txt`) |

### Example:

```php
$logger = new Logger(new Path(__DIR__), 'txt');
```

---

## 📄 License

[MIT License](./LICENSE) © 2025 [Maatify.dev](https://maatify.dev)

---

## 🙋‍♂️ Questions or Feedback?

- Open an issue on [GitHub](https://github.com/maatify/slim-logger)

---