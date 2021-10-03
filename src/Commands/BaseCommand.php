<?php

namespace FischerEnterprise\LaravelPackageCommands\Commands;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class BaseCommand extends Command
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface $inputInterface
     */
    protected $inputInterface;

    /**
     * @var \Symfony\Component\Console\Input\OutputInterface $outputInterface
     */
    protected $outputInterface;

    /**
     * @var string $signature
     */
    protected $signature;

    /**
     * @var string $signature
     */
    protected $description;

    protected function configure()
    {
        $cmdParts = explode(' ', $this->signature);
        $name = array_shift($cmdParts);

        $options = [];
        $arguments = [];

        foreach ($cmdParts as $part) {
            if (!str_starts_with($part, '{') && str_ends_with($part, '}')) {
                throw new RuntimeException("Invalid command signature: '{$this->signature}'");
            }

            $part = substr($part, 1, -1);

            $optional = str_ends_with($part, '?');
            if ($optional) {
                $part = substr($part, 0, -1);
            }

            $valueFlag = str_ends_with($part, '=');
            if ($valueFlag) {
                $part = substr($part, 0, -1);
            }

            if (str_starts_with($part, '--')) {
                $option = [];
                if (str_contains($part, '|')) {
                    [$option['name'], $option['short']] = explode('|', $part);
                } else {
                    $option['name'] = $part;
                    $option['short'] = null;
                }
                $option['default'] = $valueFlag ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_NONE;
                $options[] = $option;
            } else {
                $argument = [];
                $argument['name'] = $part;
                $argument['default'] = $optional ? InputArgument::OPTIONAL : InputArgument::REQUIRED;
                $arguments[] = $argument;
            }
        }

        $this->setName($name)->setDescription($this->description);

        foreach ($arguments as $argument) {
            $this->addArgument(
                $argument['name'],
                $argument['default']
            );
        }

        foreach ($options as $option) {
            $this->addOption(
                $option['name'],
                $option['short'],
                $option['default']
            );
        }
    }

    /**
     * Execute the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Bind IO Interfaces
        $this->inputInterface = $input;
        $this->outputInterface = $output;

        // Call children execution
        return $this->executeCommand();
    }

    /**
     * Callback to execute the real command
     *
     * @return int
     */
    abstract protected function executeCommand(): int;

    /**
     * Get and input argument
     *
     * @param string $name
     * @return string|null
     */
    protected function getArgument(string $name)
    {
        return $this->inputInterface->getArgument($name);
    }

    /**
     * Get an input option
     *
     * @param string $name
     * @return string|bool
     */
    protected function getOption(string $name)
    {
        return $this->inputInterface->getOption($name) ?? false;
    }

    /**
     * Ask the user a question
     *
     * @param string $question
     * @param string $default
     * @return string
     */
    protected function ask(string $question, string $default = null)
    {
        $questionText = $question;
        if ($default !== null) {
            $questionText .= " [$default]";
        }
        $questionText .= ': ';

        $questionHelper = $this->getHelper('question');
        return $questionHelper->ask($this->inputInterface, $this->outputInterface, new Question($questionText, $default));
    }

    /**
     * Ask the user for confirmation
     *
     * @param string $question
     * @param bool $default
     * @return bool
     */
    protected function confirm(string $question, bool $default = false)
    {
        $questionText = $question;
        $questionText .= $default ? '[Y/n]' : '[y/N]';
        $questionText .= ': ';

        $questionHelper = $this->getHelper('question');
        return $questionHelper->ask($this->inputInterface, $this->outputInterface, new ConfirmationQuestion($questionText, $default));
    }

    #region Get Printable Logo

    /**
     * Get the logo to display in console
     *
     * @return string
     */
    protected function getPrintableLogo(): string
    {
        return '<fg=red>
.____                                    .__
|    |   _____ ____________ ___  __ ____ |  |
|    |   \__  \\\\_  __ \__  \\\\  \/ // __ \|  |
|    |___ / __ \|  | \// __ \\\\   /\  ___/|  |__
|_______ (____  /__|  (____  /\_/  \___  >____/
        \/    \/           \/          \/
__________                __
\______   \_____    ____ |  | _______     ____   ____
 |     ___/\__  \ _/ ___\|  |/ /\__  \   / ___\_/ __ \
 |    |     / __ \\\\  \___|    <  / __ \_/ /_/  >  ___/
 |____|    (____  /\___  >__|_ \(____  /\___  / \___  >
                \/     \/     \/     \//_____/      \/
_________                                           .___
\_   ___ \  ____   _____   _____ _____    ____    __| _/______
/    \  \/ /  _ \ /     \ /     \\\\__  \  /    \  / __ |/  ___/
\     \___(  <_> )  Y Y  \  Y Y  \/ __ \|   |  \/ /_/ |\___ \
 \______  /\____/|__|_|  /__|_|  (____  /___|  /\____ /____  >
        \/             \/      \/     \/     \/      \/    \/
</>';
    }

    #endregion Get Printable Logo

    /**
     * Write a simple message to the console
     *
     * @param string $message
     * @return void
     */
    protected function say(string $message): void
    {
        $this->outputInterface->write($message . PHP_EOL);
    }

    /**
     * Write an information message to the console
     *
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->outputInterface->write("<fg=cyan>$message</>" . PHP_EOL);
    }

    /**
     * Write a warning message to the console
     *
     * @param string $message
     * @return void
     */
    protected function warn(string $message): void
    {
        $this->outputInterface->write("<bg=yellow>$message</>" . PHP_EOL);
    }

    /**
     * Write an error message to the console
     *
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->outputInterface->write("<bg=red;fg=white>$message</>" . PHP_EOL);
    }

}
