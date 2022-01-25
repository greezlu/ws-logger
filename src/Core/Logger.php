<?php
/** Copyright github.com/greezlu */

declare(strict_types = 1);

namespace WebServer\Core;

use WebServer\Interfaces\LoggerInterface;
use Exception;

/**
 * @package greezlu/ws-logger
 */
class Logger implements LoggerInterface
{
    protected const MESSAGE_TYPE_LIST = [
        'INFO',
        'WARNING',
        'CRITICAL'
    ];

    protected const WORKING_DIR = '../var/log/';

    /**
     * Contains descriptor of the opened file.
     * @var resource
     */
    private $fileDescriptor;

    /**
     * @var string
     */
    private string $fileName;

    /**
     * @param string|null $fileName File name without extension.
     */
    public function __construct(
        string $fileName = null
    ){
        if (!is_dir(static::WORKING_DIR)) {
            mkdir(static::WORKING_DIR, 0775, true);
        }

        $this->fileName = $fileName ?? date("Y-m-d");
    }

    /**
     * Open file and store its descriptor.
     * One file name per day by default.
     *
     * @return void
     */
    private function open(): void
    {
        $filePath = static::WORKING_DIR . $this->fileName . '.log';

        $fileNew = !file_exists($filePath);

        if ($this->fileDescriptor && !$fileNew) {
            return;
        }

        try {
            set_error_handler(function() {
                throw new Exception();
            });

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $this->fileDescriptor = fopen($filePath, 'ab');
            } else {
                $this->fileDescriptor = fopen($filePath, 'at');
            }

            if (!$this->fileDescriptor) {
                throw new Exception();
            }
        } catch (Exception $error) {
            return;
        } finally {
            restore_error_handler();
        }

        if ($fileNew) {
            chmod($filePath, 0775);
        }
    }

    /**
     * Close file descriptor.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->fileDescriptor) {
            fclose($this->fileDescriptor);
        }
    }

    /**
     * Writing message to log file.
     *
     * @param string $message Message to write in log.
     * @param int|null $code Message code.
     * @return void
     */
    public function log(string $message, int $code = 0): void
    {
        $this->open();

        if (!$this->fileDescriptor
            || !count(static::MESSAGE_TYPE_LIST)
            || !isset(static::MESSAGE_TYPE_LIST[$code])
        ) {
            return;
        }

        $type = static::MESSAGE_TYPE_LIST[$code];

        $message = '[' . date('Y-m-d H:i:s') . "] $type: $message" . PHP_EOL;

        flock($this->fileDescriptor, LOCK_EX);

        fwrite($this->fileDescriptor, $message);
        fflush($this->fileDescriptor);

        flock($this->fileDescriptor, LOCK_UN);
    }
}
