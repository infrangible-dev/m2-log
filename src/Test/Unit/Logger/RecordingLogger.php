<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\Log\Test\Unit\Logger;

use Psr\Log\AbstractLogger;
use Stringable;

/**
 * A logger for the tests that collects its records as level, message and context.
 */
class RecordingLogger extends AbstractLogger
{
    /** @var array<int, array{0: mixed, 1: string, 2: array<int|string, mixed>}> */
    private array $records = [];

    /**
     * Collects the record.
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
        $this->records[] = [$level, (string)$message, $context];
    }

    /**
     * Returns the collected records.
     *
     * @return array
     * @phpstan-return array<int, array{0: mixed, 1: string, 2: array<int|string, mixed>}>
     */
    public function getRecords(): array
    {
        return $this->records;
    }
}
