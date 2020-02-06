<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Gidato\Console\Commands\Concerns\CallsCommandsAndCollectsOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Gidato\Console\Output\OutputCollector;
use Mockery;
use stdClass;
use ReflectionProperty;

class CallsCommandsAndCollectsOutputTest extends TestCase
{

    /** @test */
    public function command_passed_to_runCommand_with_output_collector()
    {
        $checkCallMock = Mockery::mock(stdClass::class);
        $checkCallMock->shouldReceive('runCommand')
            ->with('test:command', ['args'], Mockery::type(OutputCollector::class))
            ->andReturn(2);

        $output = Mockery::mock(OutputInterface::class);
        $command = new TestCommand($checkCallMock, $output);
        $this->assertEquals(2, $command->callAndCollect($response, 'test:command', ['args']));
        $this->assertInstanceOf(OutputCollector::class, $response);
        $this->assertEquals($output, $this->getResponseOutput($response));

        $this->assertEquals(2, $command->callSilentAndCollect($response, 'test:command', ['args']));
        $this->assertInstanceOf(OutputCollector::class, $response);
        $this->assertInstanceOf(NullOutput::class, $this->getResponseOutput($response));
    }

    private function getResponseOutput(OutputCollector $response) : OutputInterface
    {
        $reflectionProperty = new ReflectionProperty(OutputCollector::class, 'output');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($response);
    }

}

class TestCommand
{
    use CallsCommandsAndCollectsOutput;

    private $runCommandMock;
    private $output;

    public function __construct($runCommandMock, $outputMock)
    {
        $this->output = $outputMock;
        $this->runCommandMock = $runCommandMock;
    }

    public function runCommand($a, $b, $c)
    {
        return $this->runCommandMock->runCommand($a, $b, $c);
    }
}
