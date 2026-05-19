<?php

namespace Console;

class SampleJob
{
    /**
     * Run custom commands
     *
     * @return void
     */
    public function handle(): void
    {
        \App\Helper::log('Sample job ran successfully!');
    }
}
