<?php

declare(strict_types=1);

namespace MarcinJozwikowski\ConsoleDatePicker\Picker;

use DateTime;
use MarcinJozwikowski\ConsoleDatePicker\Display\DatePickerDisplayInterface;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatePicker
{
    private const READ_CHAR_UP    = 'A';
    private const READ_CHAR_DOWN  = 'B';
    private const READ_CHAR_LEFT  = 'D';
    private const READ_CHAR_RIGHT = 'C';
    private const ESCAPE          = "\033";
    private const RETURN          = "\n";

    /** @var resource|null */
    private                 $inputStream;
    private OutputInterface $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        if ($input instanceof StreamableInputInterface && $stream = $input->getStream()) {
            $this->inputStream = $stream;
        } else {
            $this->inputStream = \STDIN;
        }
        $this->output = $output;
    }

    public function getDate(DatePickerDisplayInterface $pickerDisplay, string $prompt): DateTime
    {
        $this->output->write(trim($prompt) . ' ');

        $sttyMode = shell_exec('stty -g');
        $cursor   = new Cursor($this->output, $this->inputStream);
        $cursor->savePosition();
        shell_exec('stty -icanon -echo');

        while (!feof($this->inputStream)) {
            $cursor->restorePosition();
            $this->output->write($pickerDisplay->getDisplayString());
            $c = fread($this->inputStream, 1);
            if (self::ESCAPE === $c) { // escape sequence - might be one of array keys
                $c .= fread($this->inputStream, 2);
                switch ($c[2]) {
                    case self::READ_CHAR_UP:
                        $pickerDisplay->addToCurrentField();
                        break;
                    case self::READ_CHAR_DOWN:
                        $pickerDisplay->subtractFromCurrentField();
                        break;
                    case self::READ_CHAR_LEFT:
                        $pickerDisplay->previousField();
                        break;
                    case self::READ_CHAR_RIGHT:
                        $pickerDisplay->nextField();
                        break;
                }
            } elseif (self::RETURN === $c) {
                // Return has been pressed
                break;
            }
        }

        shell_exec(sprintf('stty %s', $sttyMode));

        return $pickerDisplay->getValue();
    }
}