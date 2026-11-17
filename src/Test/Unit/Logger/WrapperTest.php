<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\Log\Test\Unit\Logger;

use Infrangible\Log\Logger\Wrapper;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Tests that the wrapper passes every record to the default logger and to the added loggers.
 */
class WrapperTest extends TestCase
{
    /**
     * Tests that the default logger gets the records of every level.
     *
     * @return void
     */
    public function testDefaultLoggerGetsAllLevels(): void
    {
        $defaultLogger = new RecordingLogger();

        $wrapper = new Wrapper($defaultLogger);

        $wrapper->emergency('emergency');
        $wrapper->alert('alert');
        $wrapper->critical('critical');
        $wrapper->error('error');
        $wrapper->warning('warning');
        $wrapper->notice('notice');
        $wrapper->info('info');
        $wrapper->debug('debug', ['key' => 'value']);
        $wrapper->log(LogLevel::INFO, 'log');

        $this->assertSame(
            [
                [LogLevel::EMERGENCY, 'emergency', []],
                [LogLevel::ALERT, 'alert', []],
                [LogLevel::CRITICAL, 'critical', []],
                [LogLevel::ERROR, 'error', []],
                [LogLevel::WARNING, 'warning', []],
                [LogLevel::NOTICE, 'notice', []],
                [LogLevel::INFO, 'info', []],
                [LogLevel::DEBUG, 'debug', ['key' => 'value']],
                [LogLevel::INFO, 'log', []]
            ],
            $defaultLogger->getRecords()
        );
    }

    /**
     * Tests that added loggers get the records after the default logger, and that values that are no loggers and the
     * wrapper itself are ignored.
     *
     * @return void
     */
    public function testAddedLoggers(): void
    {
        $defaultLogger = new RecordingLogger();
        $moduleLogger = new RecordingLogger();

        $wrapper = new Wrapper($defaultLogger);

        $wrapper->addLoggers([$moduleLogger, 'no logger', $wrapper]);
        $wrapper->error('error');

        $this->assertSame([$moduleLogger, $defaultLogger], $wrapper->getLoggers());
        $this->assertSame([[LogLevel::ERROR, 'error', []]], $defaultLogger->getRecords());
        $this->assertSame([[LogLevel::ERROR, 'error', []]], $moduleLogger->getRecords());
    }

    /**
     * Tests that the loggers are initialized only once, also if a logger logs while it is added.
     *
     * @return void
     */
    public function testInitializedOnce(): void
    {
        $defaultLogger = new RecordingLogger();

        $wrapper = new class ($defaultLogger) extends Wrapper {
            /** @var int */
            public int $initializations = 0;

            /**
             * @return void
             */
            public function initialize(): void
            {
                $this->initializations++;

                parent::initialize();

                $this->info('initialized');
            }
        };

        $wrapper->info('first');
        $wrapper->info('second');

        $this->assertSame(1, $wrapper->initializations);
        $this->assertSame(
            [[LogLevel::INFO, 'initialized', []], [LogLevel::INFO, 'first', []], [LogLevel::INFO, 'second', []]],
            $defaultLogger->getRecords()
        );
    }

    /**
     * Tests that the wrapper without default logger logs nothing.
     *
     * @return void
     */
    public function testWithoutDefaultLogger(): void
    {
        $wrapper = new Wrapper();

        $wrapper->critical('critical');

        $this->assertSame([], $wrapper->getLoggers());
    }
}
