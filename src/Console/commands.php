<?php

namespace Console;

spl_autoload_register(function ($class_name) {
    require_once '/var/www/src/' . str_replace('\\', '/', $class_name) . '.php';
});
require_once '/var/www/env.php';

/**
 * Check if current time match with request and then run the commands
 * Consider a bit delay
 */
$timingMapping = [
    'everyMinute' => idate('s') < 2 ? 1 : 0,
    'everyFiveMinutes' => idate('s') < 2 && idate('i') % 5 == 0 ? 1 : 0,
    'everyFifteenMinutes' => idate('s') < 2 && idate('i') % 15 == 0 ? 1 : 0,
    'hourly' => idate('s') < 2 && idate('i') == 0 ? 1 : 0,
    'weekly' => idate('s') < 2 && idate('i') == 0 && idate('H') == 0 && idate('w') == 1 ? 1 : 0,
    'monthly' => idate('s') < 2 && idate('i') == 0 && idate('H') == 0 && idate('d') == 1 ? 1 : 0,
    'quarterly' => idate('s') < 2 && idate('i') == 0 && idate('H') == 0 && idate('d') == 1 && idate('m') % 4 == 0 ? 1 : 0,
    'yearly' => idate('s') < 2 && idate('i') == 0 && idate('H') == 0 && idate('d') == 1 && idate('m') == 1 ? 1 : 0,
];

/**
 * Add your script file name and custom timing in here
 */
$commands = [
    'SampleJob' => 'everyMinute',
];

/**
 * Run script if existed and match with timing
 */
foreach ($commands as $k => $v) {
    if ($timingMapping[$v]) {
        $className = '\\Console\\' . $k;
        $class = new $className();
        $class->handle();
    }
}
