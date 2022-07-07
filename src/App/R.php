<?php

namespace App;

/**
 * Class R
 * @package App
 */
class R
{
    /**
     * Run R script
     * You can add your script in a R file like this:
     *
     *       # Create the data for the chart
     *       H <- c(7,12,28,3,41)
     *
     *       # Give the chart file a name
     *       png(file = "barchart.png")
     *
     *       # Plot the bar chart
     *       barplot(H)
     *
     *       # Save the file
     *       dev.off()
     *
     * Then you can call and run the script file like this:
     *
     *      R::runScript('barchart.R');
     *
     * The generated barchart.png can be used after execution from the specified path
     *
     * @param string $script
     * @param string $args
     * @return bool|string|null
     */
    public static function runScript(string $script, string $args = ''): bool|string|null
    {
        return shell_exec("Rscript $script $args");
    }
}
