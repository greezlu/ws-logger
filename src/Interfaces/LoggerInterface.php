<?php
/** Copyright github.com/greezlu */

declare(strict_types = 1);

namespace WebServer\Interfaces;

/**
 * @package greezlu/ws-logger
 */
interface LoggerInterface
{
    /**
     * @param string $message
     * @param int|null $code
     * @return void
     */
    public function log(string $message, int $code = 0): void;
}
