<?php
// Thanks for great codes: https://gist.github.com/im4aLL/548c11c56dbc7267a2fe96bda6ed348b

namespace App;

/**
 * Class Event
 * @package App
 */
class Event
{
    private static array $events = [];

    /**
     * Register event listener name and a function for callback
     *
     * @param $name
     * @param $callback
     */
    public static function listen($name, $callback)
    {
        self::$events[$name][] = $callback;
    }

    /**
     * Trigger a registered listener
     *
     * @param $name
     * @param null $argument
     */
    public static function trigger($name, $argument = null)
    {
        foreach (self::$events[$name] as $callback) {
            if ($argument && is_array($argument)) {
                call_user_func_array($callback, $argument);
            } elseif ($argument && !is_array($argument)) {
                call_user_func($callback, $argument);
            } else {
                call_user_func($callback);
            }
        }
    }
}
