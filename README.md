# Gidato / Laravel-Console-Output

This provides an extensions to the console commands, to allow other commands to be called,
but the output to be collected rather than printed to the screen.

As the normal output converts the output to a string, this allows objects to returned
provided they have the \__toString() method

## Installation
```

composer require gidato/laravel-console-output

```

## Example Use

```
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Gidato\Console\Commands\Concerns\CallsCommandsAndCollectsOutput;

class MyCommand extends Command
{
    \\ include the trait
    use CallsCommandsAndCollectsOutput;

    .
    .
    .

    public function handle()
    {
        $this->callAndCollect($response, 'another:command');
        $lines = $response->getLines();

        $this->callSilentAndCollect($response, 'another:command');
        $lines = $response->getLines();

    }
}
```

In each of these call commands, the response from the underlying command is returned, and can be checked.

The output can then be retrieved from the response by using the getLines() method.


## License

This software is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
