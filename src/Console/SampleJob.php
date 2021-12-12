<?php

namespace Console;

class SampleJob
{
    /**
     * Run custom commands
     */
    function handle()
    {
        \App\Helper::log('Sample job ran successfully!');
    }
}
