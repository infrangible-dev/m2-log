# Infrangible Log

Passes every log record of Magento to several loggers, so that modules can write records into their own log files
in addition to Magento's.

The module replaces the logger of Magento (`Psr\Log\LoggerInterface`) with `Infrangible\Log\Logger\Wrapper`. Its first
logger is Magento's logger, so installing the module alone changes nothing. Modules add more loggers with a plugin.

## Adding loggers

Before the first record is logged, the wrapper calls its method `initialize()`, which adds Magento's logger. A plugin
after this method adds the loggers of a module. Each logger implements `Psr\Log\LoggerInterface`; other values are
ignored.

In the `di.xml` of the module:

```xml
<type name="Infrangible\Log\Logger\Wrapper">
    <plugin name="vendor_module_logging" type="Vendor\Module\Plugin\Logging"/>
</type>
```

The plugin:

```php
<?php

declare(strict_types=1);

namespace Vendor\Module\Plugin;

use Infrangible\Log\Logger\Wrapper;
use Vendor\Module\Logger\ErrorLog;
use Vendor\Module\Logger\Log;

class Logging
{
    public function __construct(
        private readonly Log $log,
        private readonly ErrorLog $errorLog
    ) {
    }

    public function afterInitialize(Wrapper $wrapper): void
    {
        $wrapper->addLoggers([$this->log, $this->errorLog]);
    }
}
```

The loggers can be instances of `Magento\Framework\Logger\Monolog` with their own handlers, for example
`Magento\Framework\Logger\Handler\Base` with another file name, configured as virtual types in `di.xml`.

`getLoggers()` returns all loggers of the wrapper.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.6 or newer
- PHP 8.2 or newer

## Installation

```bash
composer require infrangible/m2-log
bin/magento module:enable Infrangible_Log
bin/magento setup:upgrade
```

In production mode, also run `bin/magento setup:di:compile`.

## License

Infrangible Log is licensed under the MIT License - see the LICENSE file for details.
