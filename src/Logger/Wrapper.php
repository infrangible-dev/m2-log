<?php

namespace Infrangible\Log\Logger;

use Psr\Log\LoggerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Wrapper implements LoggerInterface
{
    private ?LoggerInterface $defaultLogger;

    /** @var LoggerInterface[] */
    private array $loggers = [];

    private bool $initialized = false;

    /**
     * @param LoggerInterface|null $defaultLogger
     */
    public function __construct(LoggerInterface $defaultLogger = null)
    {
        $this->defaultLogger = $defaultLogger;
    }

    protected function checkInitialized(): void
    {
        if (! $this->initialized) {
            $this->initialize();
            $this->initialized = true;
        }
    }

    public function initialize(): void
    {
        if ($this->defaultLogger !== null) {
            $this->addLoggers([$this->defaultLogger]);
        }
    }

    /**
     * @param LoggerInterface[] $loggers
     */
    public function addLoggers(array $loggers = []): void
    {
        foreach ($loggers as $logger) {
            if ($logger instanceof LoggerInterface) {
                $this->loggers[] = $logger;
            }
        }
    }

    /**
     * System is unusable.
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->emergency(
                $message,
                $context
            );
        }
    }

    /**
     * Action must be taken immediately.
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->alert(
                $message,
                $context
            );
        }
    }

    /**
     * Critical conditions.
     * Example: Application component unavailable, unexpected exception.
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->critical(
                $message,
                $context
            );
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->error(
                $message,
                $context
            );
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->warning(
                $message,
                $context
            );
        }
    }

    /**
     * Normal but significant events.
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->notice(
                $message,
                $context
            );
        }
    }

    /**
     * Interesting events.
     * Example: User logs in, SQL logs.
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->info(
                $message,
                $context
            );
        }
    }

    /**
     * Detailed debug information.
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->debug(
                $message,
                $context
            );
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->checkInitialized();

        foreach ($this->loggers as $logger) {
            $logger->log(
                $level,
                $message,
                $context
            );
        }
    }
}
