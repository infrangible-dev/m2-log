# Infrangible Log

## Introduction

This module was written to enable the possibility of using multiple loggers to log a record into several log files.

If you simply install this module nothing will change because the Magento default logger will be added as first logger.
You can add more loggers using a plugin as described.

## Usage

### Code

The following example is based on a feature module using this base module.

First you need to add a plugin definition in the di.xml of your module.

```
<type name="Infrangible\Log\Logger\Wrapper">
    <plugin name="module-plugin" type="Infrangible\Module\Plugin\Logging" sortOrder="10" disabled="false"/>
</type>
```

In this plugin you can add as many additional loggers as you like. In this case four more loggers are added. The loggers
itself are included via dependency injection of Magento. Each logger has to implement the Interface ``LoggerInterface``.

```
<?php

namespace Infrangible\Module\Plugin;

use Infrangible\Module\Logger\Monolog\ErrorLog;
use Infrangible\Module\Logger\Monolog\Log;
use Infrangible\Module\Logger\Monolog\SuccessLog;
use Infrangible\Log\Logger\Wrapper;

/**
 * @author Andreas Knollmann
 */
class Logging
{
    /** @var Log */
    private $moduleLog;

    /** @var ErrorLog */
    private $moduleErrorLog;

    /** @var SuccessLog */
    private $moduleSuccessLog;

    /**
     * @param Log        $moduleLog
     * @param ErrorLog   $moduleErrorLog
     * @param SuccessLog $moduleSuccessLog
     */
    public function __construct(Log $moduleLog, ErrorLog $moduleErrorLog, SuccessLog $moduleSuccessLog)
    {
        $this->moduleLog = $moduleLog;
        $this->moduleErrorLog = $moduleErrorLog;
        $this->moduleSuccessLog = $moduleSuccessLog;
    }

    /**
     * @param Wrapper $wrapper
     */
    public function afterInitialize(Wrapper $wrapper)
    {
        $wrapper->addLoggers([$this->moduleLog, $this->moduleErrorLog, $this->moduleSuccessLog]);
    }
}
```

## License

Infran Log is licensed under the MIT License - see the LICENSE file for details.
