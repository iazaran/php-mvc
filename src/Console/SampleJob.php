<?php

namespace Console;

class SampleJob
{
    /**
     * Run custom commands
     *
     * @return void
     */
    function handle(): void
    {
        \App\Helper::log('Sample job ran successfully!');
    }
}
