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
- ✅ Uses `LogLevelEnum` (PHP 8.1+)
- ✅ Logs to file in JSON format
- ✅ File names include log level (`_response_error_...`)
- ✅ Static access with `Logger::recordStatic()`
- ✅ Automatically creates secure log directories
- ✅ Works with Slim or pure PHP
- ✅ GitHub Actions CI ready

---

## 📦 Installation

```bash
composer require maatify/slim-logger
```

---

## 🧱 Namespaces

- Logger: `Maatify\SlimLogger\Log\Logger`
- Enum: `Maatify\SlimLogger\Log\LogLevelEnum`
- Path Helper: `Maatify\SlimLogger\Store\File\Path`

---

## 📁 Folder Structure

```
maatify-slim-logger/
├── src/
│   └── Log/
│       ├── Logger.php
│       └── LogLevelEnum.php
│   └── Store/
│       └── File/
│           └── Path.php
```

---

## ✅ Basic Usage (Slim or Plain PHP)

```php
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Log\LogLevelEnum;
use Maatify\SlimLogger\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
$logger->record('User login', null, 'user/actions', LogLevelEnum::Info);
```

---

## 💡 Usage in Slim Route

```php
$app->post('/action', function ($request, $response) use ($logger) {
    $logger->record('User posted action.', $request, 'user/submit', LogLevelEnum::Debug);
    return $response;
});
```

---

## ⚠️ Logging Exceptions

```php
try {
    throw new \Exception('Oops');
} catch (\Throwable $e) {
    $logger->record($e, null, 'errors/runtime', LogLevelEnum::Error);
}
```

---

## 📣 Static Logging

Log from anywhere — no need to instantiate:

```php
use Maatify\SlimLogger\Log\Logger;
use Maatify\SlimLogger\Log\LogLevelEnum;

Logger::recordStatic('Static log entry.', null, 'system/status', LogLevelEnum::Info);
```

### ✅ Static Exception Example

```php
try {
    throw new \Exception("Static exception!");
} catch (\Throwable $e) {
    Logger::recordStatic($e, null, 'errors/fatal', LogLevelEnum::Error);
}
```

---

## 🧪 (Advanced) Overriding Static Instance for Testing

In PHPUnit or setup environments, use:

```php
$testLogger = new Logger(new Path(__DIR__ . '/logs'));
Logger::setInstance($testLogger);
```

Then `recordStatic()` will use that injected instance and path.

---

## 🔍 Log File Naming Example

```text
/logs/24/04/18/user/actions_response_info_20250418AM.log
```

---

## ⚙️ Configuration

| Option        | Default        | Description                     |
|---------------|----------------|---------------------------------|
| `Path`        | project root   | Base path for log files         |
| `Extension`   | `.log`         | File extension for log files    |

---

## 🧪 Run Tests Locally

```bash
composer test
```

---

## 🚀 GitHub CI Integration

Tests run automatically on push via:

`.github/workflows/run-tests.yml`

---

## 📄 License

[MIT License](./LICENSE) © 2025 [Maatify.dev](https://maatify.dev)

---

## 🙋‍♂️ Questions?

- GitHub: [github.com/maatify/slim-logger](https://github.com/maatify/slim-logger)

---
