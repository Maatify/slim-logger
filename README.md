[![Current version](https://img.shields.io/packagist/v/maatify/slim-logger)](https://packagist.org/packages/maatify/slim-logger)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/logger)](https://packagist.org/packages/maatify/slim-logger)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/slim-logger)](https://packagist.org/packages/maatify/slim-logger/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/slim-logger)](https://packagist.org/packages/maatify/slim-logger/stats)

# Maatify Slim Logger

**MaatifySlimLogger** is a lightweight, PSR-7 compatible, Slim-friendly structured logger for PHP applications. It is part of the modular [Maatify](https://maatify.dev) ecosystem.

---

## 🚀 Features

- ✅ PSR-7 request-aware logging (integrates with Slim Framework)
- ✅ Logs to file in JSON format
- ✅ Date-based folder structure
- ✅ Automatically creates secure log directories
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

- Logger class: `Maatify\SlimLogger\Logger`
- Path helper: `Maatify\Store\File\Path`


Autoloaded via PSR-4.

---

## 📁 Folder Structure

```
maatify-slim-logger/
├── src/
│   └── SlimLogger/
│       └── Logger.php
│   └── Store/
│       └── File/
│           └── Path.php
```

---

## ✅ How It Works

`Logger`:
- Writes **JSON-formatted** logs to files.
- Organizes logs by date in nested folders (`/logs/yy/mm/dd/...`)
- Supports optional logging of **PSR-7 HTTP request context**.
- Works in **Slim** or **pure PHP**.

---

## 💡 Usage for Slim Developers

### 1. Inject or Instantiate Logger

```php
use Maatify\SlimLogger\Logger;
use Maatify\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
```

### 2. Use in a Route

```php
$app->get('/log', function ($request, $response) use ($logger) {
    $logger->record('User accessed log route.', $request, 'api/user/logs');

    $response->getBody()->write("Log recorded.");
    return $response;
});
```

---

### 📝 What Gets Logged?

```json
{
  "log_details": {
    "message": "User accessed log route."
  },
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

You can still use the logger outside Slim — just omit the request.

### 1. Setup

```php
require 'vendor/autoload.php';

use Maatify\SlimLogger\Logger;
use Maatify\Store\File\Path;

$logger = new Logger(new Path(__DIR__));
```

### 2. Record Logs

```php
$logger->record('Something happened in pure PHP.', null, 'scripts/cronjob');
```

---

## ⚠️ Logging Exceptions

The `record()` method handles exceptions and arrays:

```php
try {
    throw new \Exception('Oops, something failed.');
} catch (\Throwable $e) {
    $logger->record($e, null, 'errors');
}
```

---

## 🔍 Log File Output

Logs are saved like this:

```
/logs/
└── 24/
    └── 04/
        └── 17/
            └── api_user_logs_2024041713.log
```

Each log file appends new records hourly (based on current hour like `2024041713`).

---

## ⚙️ Optional Configuration

| Option      | Default        | Description                   |
|-------------|----------------|-------------------------------|
| `Path`      | `project/logs` | Base log directory            |
| `Extension` | `.log`         | Log file extension (e.g. txt) |

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
```
