<?php

namespace Gidato\Console\Output;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * Passess everything to the decorated OutputInterface class passed in
 * EXCEPT for messages passed to writeln.
 * These are checked to see if they contain styling, and if so they
 * are also passed to the decorated OutputInterface class.
 * However, if not, the entry is stored in the clase, and is accessible via getLines later.
 */

class OutputCollector implements OutputInterface
{
    private $output;
    private $lines;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->lines = [];
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function writeln($messages, $options = 0)
    {
        if (
            preg_match('/^\<([^\>]+?)\>./', $messages, $matches) &&
            "</{$matches[1]}>" == substr($messages,-strlen($matches[1])-3)
        ) {
            // line is wrapped in a tag.
            return $this->output->writeln($messages, $options);
        }
        $this->lines[] = $messages;
    }

    /**
     * All methods below pass the request to the output provided at construction
     * These are all required for the interface.
     */
    public function write($messages, $newline = false, $options = 0)
    {
        return $this->output->write($messages, $newline, $options);
    }

    public function setVerbosity($level)
    {
        return $this->output->setVerbosity($level);
    }

    public function getVerbosity()
    {
        return $this->output->getVerbosity();
    }

    public function isQuiet()
    {
        return $this->output->isQuiet();
    }

    public function isVerbose()
    {
        return $this->output->isVerbose();
    }

    public function isVeryVerbose()
    {
        return $this->output->isVeryVerbose();
    }

    public function isDebug()
    {
        return $this->output->isDebug();
    }

    public function setDecorated($decorated)
    {
        return $this->output->setDecorated($decorated);
    }

    public function isDecorated()
    {
        return $this->output->isDecorated();
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        return $this->output->setFormatter($formatter);
    }

    public function getFormatter()
    {
        return $this->output->getFormatter();
    }
}
