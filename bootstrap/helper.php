<?php
/**
 * Log exception to laravel log file
 */
if (!function_exists('exception_logger')) {
    function exception_logger(Exception $e, array $data = [], $message = '')
    {
        logger()->error(
            $e->getMessage() . " [{$message}]",
            [
                $e->getFile(),
                $e->getLine(),
                $data
            ]
        );
    }
}
