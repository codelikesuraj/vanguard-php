# Vanguard SDK
**Vanguard** is a lightweight PHP SDK for capturing errors and exceptions in your PHP applications and sending them to the **Vanguard Dashboard**.
It's designed to be framework-agnostic, with as little external dependencies as possibly, so it can plug into any PHP project.

## Features
- Captures uncaught exceptions and PHP runtime errors.
- Normalize exceptions and errors into structured events.
- Fingerprint events for grouping.
- Logs to a local JSON file or send events to the Vanguard Dashboard API

## Installation
```bash
composer require codelikesuraj/vanguard
```

## Usage
```php
require 'vendor/autoload.php';

use Codelikesuraj\Vanguard\Vanguard;
use Codelikesuraj\Vanguard\Transport\FileTransport;

// log to local file
$transport = new FileTransport(__DIR__ . '/VANGUARD_LOG_FILE'));

/** OR **/

// log to Vanguard dashboard
$transport = new HttpTransport("https://VANGUARD_PROJECT_DSN")


// Initialize vanguard and automatically capture errors and exceptions
Vanguard::init($transport);

// Manually capture and exception
Vanguard::capture(Throwable $e, array $context = [])
    ->transport($transport)   // optional
```

## How it works
1. ``` Vanguard::init() ``` registers global handlers:
   - **Errors** via set_error_handler()
   - **Exceptions** via set_exception_handler()
2. Every captured error and exception is turned into an Event with:
    - Context (custom metadata)
    - Message
    - File path
    - Fingerprint (used for grouping)
    - Line number
    - Type (Exception, Error etc)
    - Stacktrace
    - Timestamp
3. The chosen **Transport** (File/Http) then decides where the event goes.

## Example Event JSON
```json
{
  "context": [],
  "fingerprint": "24bb5912458f9dd1fe4bc1843a17c2c261306fff",
  "message": "strlen(): Argument #1 ($string) must be of type string, array given",
  "file": "/var/www/index.php",
  "line": 12,
  "stacktrace": [
    {"file": "/var/www/index.php", "line": 12, "function": "strlen"}
  ],
  "timestamp": "2025-08-17T20:11:49+01:00",
  "type": "TypeError"
}
```

## Roadmap
- [ ] Basic Vanguard dashboard - provide API for logging events per project
- [ ] SDK for other languages (JS, Go)
- [ ] will add others as needed
    