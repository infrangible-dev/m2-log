<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\Log\Logger;

use Psr\Log\LoggerInterface;
use Stringable;

/**
 * Passes every log record to several loggers.
 *
 * The first logger is the default logger, Magento's logger. Other modules add their loggers with a plugin after the
 * method initialize(), which is called before the first record is logged.
 */
class Wrapper implements LoggerInterface
{
    /** @var LoggerInterface[] */
    private array $loggers = [];

    /** @var bool */
    private bool $initialized = false;

    /**
     * @param LoggerInterface|null $defaultLogger
     */
    public function __construct(private readonly ?LoggerInterface $defaultLogger = null)
    {
    }

    /**
     * Initializes the loggers before the first record is logged.
     *
     * @return void
     */
    protected function checkInitialized(): void
    {
        if (! $this->initialized) {
            $this->initialized = true;
            $this->initialize();
        }
    }

    /**
     * Adds the default logger. Plugins after this method add more loggers.
     *
     * @return void
     */
    public function initialize(): void
    {
        if ($this->defaultLogger !== null) {
            $this->addLoggers([$this->defaultLogger]);
        }
    }

    /**
     * Adds loggers. Values that are no loggers, and the wrapper itself, are ignored.
     *
     * @param array $loggers
     * @phpstan-param array<int|string, mixed> $loggers
     *
     * @return void
     */
    public function addLoggers(array $loggers = []): void
    {
        foreach ($loggers as $logger) {
            if ($logger instanceof LoggerInterface && $logger !== $this) {
                $this->loggers[] = $logger;
            }
        }
    }

    /**
     * Returns the loggers that get the records.
     *
     * @return LoggerInterface[]
     */
    public function getLoggers(): array
    {
        $this->checkInitialized();

        return $this->loggers;
    }

    /**
     * System is unusable.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->emergency($message, $context);
        }
    }

    /**
     * Action must be taken immediately, for example when the entire website is down.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->alert($message, $context);
        }
    }

    /**
     * Critical conditions, for example an unavailable component or an unexpected exception.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->critical($message, $context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should be logged and monitored.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->error($message, $context);
        }
    }

    /**
     * Exceptional occurrences that are not errors, for example the use of deprecated APIs.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->warning($message, $context);
        }
    }

    /**
     * Normal but significant events.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->notice($message, $context);
        }
    }

    /**
     * Interesting events, for example a login of a customer.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->info($message, $context);
        }
    }

    /**
     * Detailed debug information.
     *
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->debug($message, $context);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|Stringable $message
     * @param array $context
     * @phpstan-param array<int|string, mixed> $context
     *
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        foreach ($this->getLoggers() as $logger) {
            $logger->log($level, $message, $context);
        }
    }
}
