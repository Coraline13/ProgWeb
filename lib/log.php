<?php
$datadir = dirname(__FILE__) . '/../data';
if (!file_exists($datadir)) {
    mkdir($datadir, 0750, false);
}
$log = fopen($datadir . '/application.log', 'at');

/**
 * @return string current date and time formatted as a string
 */
function format_timestamp()
{
    date_default_timezone_set("Europe/Paris");
    $now = new DateTime();
    return $now->format("Y/m/d H:i:s P");
}

define("LOG_LEVEL_DEBUG", "DEBUG");
define("LOG_LEVEL_INFO", "INFO");
define("LOG_LEVEL_WARNING", "WARNING");
define("LOG_LEVEL_ERROR", "ERROR");

/**
 * Possible values for the $level parameter of _log_write
 * @see _log_write()
 */
$log_levels = array(LOG_LEVEL_DEBUG, LOG_LEVEL_INFO, LOG_LEVEL_WARNING, LOG_LEVEL_ERROR);

/**
 * Write a message to the application log
 * @param $message string log message
 * @param $level string log level, one of $log_levels
 * @see $log_levels
 */
function _log_write($message, $level)
{
    global $log, $log_levels;
    if (!in_array($level, $log_levels)) {
        throw new InvalidArgumentException("$level is not a valid log level");
    }
    fprintf($log, "[%s][%s] %s\n", format_timestamp(), $level, $message);
}

/**
 * Write a message to the application log
 * @param $message string log message
 */
function log_debug($message)
{
    _log_write($message, LOG_LEVEL_DEBUG);
}

/**
 * Write a message to the application log
 * @param $message string log message
 */
function log_info($message)
{
    _log_write($message, LOG_LEVEL_INFO);
}

/**
 * Write a message to the application log
 * @param $message string log message
 */
function log_warning($message)
{
    _log_write($message, LOG_LEVEL_WARNING);
}

/**
 * Write a message to the application log
 * @param $message string log message
 */
function log_error($message)
{
    _log_write($message, LOG_LEVEL_ERROR);
}

/**
 * Format and write the given exception to the application log. Multiple log messages will be outputted, one for each
 * nested exception.
 * @param Throwable $exc the exception
 * @param string $log_level optional log level; defaults to ERROR
 */
function log_exception($exc, $log_level = LOG_LEVEL_ERROR)
{
    _log_write("unhandled exception of type " . get_class($exc) . ": " . $exc->getMessage() . "\n" . format_exception_trace($exc), $log_level);
    $cause = $exc->getPrevious();
    while ($cause !== null) {
        _log_write("previous exception was caused by " . get_class($cause) . ": " . $cause->getMessage() . "\n" . format_exception_trace($cause), $log_level);
        $cause = $cause->getPrevious();
    }
}
