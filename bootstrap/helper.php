<?php
/**
 * Log exception to laravel log file
 */
if (!function_exists('exception_logger')) {
    function exception_logger(Exception $e, array $data = [])
    {
        logger()->error(
            $e->getMessage(),
            [
                $e->getFile(),
                $e->getLine(),
                $data
            ]
        );
    }
}
