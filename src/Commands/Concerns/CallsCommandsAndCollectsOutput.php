<?php

namespace Gidato\Console\Commands\Concerns;

use Gidato\Console\Output\OutputCollector;
use Symfony\Component\Console\Output\NullOutput;

trait CallsCommandsAndCollectsOutput
{
    /**
     * Call another console command and populates the value in response.
     *
     * @param  $response
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @return int
     */
    public function callAndCollect(&$response, $command, array $arguments = [])
    {
        $response = new OutputCollector($this->output);
        return $this->runCommand($command, $arguments, $response);
    }

    /**
     * Call another console command silently, and collect base output in response.
     *
     * @param  $response
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @return int
     */
    public function callSilentAndCollect(&$response, $command, array $arguments = [])
    {
        $response = new OutputCollector(new NullOutput);
        return $this->runCommand($command, $arguments, $response);
    }
}
