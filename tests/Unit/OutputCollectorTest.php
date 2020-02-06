<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Gidato\Console\Output\OutputCollector;
use Mockery;

class OutputCollectorTest extends TestCase
{

    /** @test */
    public function ensure_app_passed_through_to_underlying_output_except_that_to_be_caught()
    {
        $output = Mockery::mock(OutputInterface::class);
        $collector = new OutputCollector($output);

        $output->shouldReceive('writeln')->with('<info>message</info>', 17)->andReturn('DONE');
        $this->assertEquals('DONE', $collector->writeln('<info>message</info>', 17));

        $output->shouldReceive('write')->with('message', false, 17)->andReturn('DONE');
        $this->assertEquals('DONE', $collector->write('message', false, 17));

        $output->shouldReceive('setVerbosity')->with(18)->andReturn('DONE');
        $this->assertEquals('DONE', $collector->setVerbosity(18));

        $output->shouldReceive('getVerbosity')->andReturn(19);
        $this->assertEquals(19, $collector->getVerbosity());

        $output->shouldReceive('isQuiet')->andReturn('sure is');
        $this->assertEquals('sure is', $collector->isQuiet());

        $output->shouldReceive('isVerbose')->andReturn('yes, it is');
        $this->assertEquals('yes, it is', $collector->isVerbose());

        $output->shouldReceive('isVeryVerbose')->andReturn('yes, it really is');
        $this->assertEquals('yes, it really is', $collector->isVeryVerbose());

        $output->shouldReceive('isDebug')->andReturn('debugging');
        $this->assertEquals('debugging', $collector->isDebug());

        $output->shouldReceive('setDecorated')->with('decorator')->andReturn('DONE');
        $this->assertEquals('DONE', $collector->setDecorated('decorator'));

        $output->shouldReceive('isDecorated')->andReturn('decorating');
        $this->assertEquals('decorating', $collector->isDecorated());

        $formatter = Mockery::mock(OutputFormatterInterface::class);
        $output->shouldReceive('setFormatter')->with($formatter)->andReturn('DONE');
        $this->assertEquals('DONE', $collector->setFormatter($formatter));

        $output->shouldReceive('getFormatter')->andReturn($formatter);
        $this->assertEquals($formatter, $collector->getFormatter());
    }

    /** @test */
    public function collect_and_provide_any_writeln_lines_not_wrapped_in_tags()
    {
        $output = Mockery::mock(OutputInterface::class);
        $collector = new OutputCollector($output);

        $collector->writeln('line 1');
        $collector->writeln($class = new StringProvidingTestClass()); // see below

        $lines = $collector->getLines();
        $this->assertEquals('line 1', $lines[0]);
        $this->assertEquals($class, $lines[1]);
        $this->assertEquals('test class', (string) $lines[1]);
    }
}

class StringProvidingTestClass
{
    public function __toString()
    {
        return 'test class';
    }
}
