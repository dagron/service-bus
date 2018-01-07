<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBus\Extensions\Logger;

use Desperado\Domain\Environment\Environment;
use Desperado\Domain\ThrowableFormatter;
use Desperado\Infrastructure\Bridge\Logger\LoggerRegistry;
use Psr\Log\LogLevel;

/**
 * Logger wrapper
 */
class ServiceBusLogger
{
    /**
     * Environment
     *
     * @var Environment|null
     */
    private static $environment;

    /**
     * Entry point name
     *
     * @var string|null
     */
    private static $entryPointName;

    /**
     * Setup environment instance
     *
     * @param Environment $environment
     *
     * @return void
     */
    public static function setupEnvironment(Environment $environment): void
    {
        self::$environment = $environment;
    }

    /**
     * Setup entry point name
     *
     * @param string $entryPointName
     *
     * @return void
     */
    public static function setupEntryPointName(string $entryPointName): void
    {
        self::$entryPointName = $entryPointName;
    }

    /**
     * Log debug message
     *
     * @param string|null $channel
     * @param string      $message
     * @param array       $extra
     *
     * @return void
     */
    public static function debug(?string $channel, string $message, array $extra = []): void
    {
        self::log($channel, $message, LogLevel::DEBUG, $extra);
    }

    /**
     * Log info message
     *
     * @param string|null $channel
     * @param string      $message
     * @param array       $extra
     *
     * @return void
     */
    public static function info(?string $channel, string $message, array $extra = []): void
    {
        self::log($channel, $message, LogLevel::INFO, $extra);
    }

    /**
     * Log error message
     *
     * @param string|null $channel
     * @param string      $message
     * @param array       $extra
     *
     * @return void
     */
    public static function error(?string $channel, string $message, array $extra = []): void
    {
        self::log($channel, $message, LogLevel::ERROR, $extra);
    }

    /**
     * Log critical message
     *
     * @param string|null $channel
     * @param string      $message
     * @param array       $extra
     *
     * @return void
     */
    public static function critical(?string $channel, string $message, array $extra = []): void
    {
        self::log($channel, $message, LogLevel::CRITICAL, $extra);
    }

    /**
     * Logging message
     *
     * @param string|null $channel
     * @param string      $message
     * @param string      $level
     * @param array       $extra
     *
     * @return void
     */
    public static function log(?string $channel, string $message, string $level = LogLevel::DEBUG, array $extra = []): void
    {
        $logger = LoggerRegistry::getLogger($channel);
        $extraData = [
            'environment' => (string) self::$environment,
            'entryPoint'  => (string) self::$entryPointName,
            'hostname'    => \gethostname()
        ];

        $logger->log($level, $message, \array_merge($extraData, $extra));
    }

    /**
     * Log Throwable details
     *
     * @param string     $channel
     * @param \Throwable $throwable
     * @param string     $level
     * @param array      $extra
     *
     * @return void
     */
    public static function throwable(
        string $channel,
        \Throwable $throwable,
        string $level = LogLevel::ERROR,
        array $extra = []
    ): void
    {
        self::log($channel, ThrowableFormatter::toString($throwable), $level, $extra);
    }
}