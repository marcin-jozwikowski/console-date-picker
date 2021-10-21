<?php

declare(strict_types=1);

use MarcinJozwikowski\ConsoleDatePicker\Display\DatePickerDisplayInterface;
use MarcinJozwikowski\ConsoleDatePicker\Picker\DatePicker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatePickerTest extends TestCase
{
    public function testEmptyInput(): void
    {
        [$picker, $pickerDisplay] = $this->getPickerMocks("");
        $picker->getDate($pickerDisplay, '');
    }

    public function testConfirmationOnly(): void
    {
        [$picker, $pickerDisplay] = $this->getPickerMocks("\n");
        $picker->getDate($pickerDisplay, '');
    }

    /**
     * @param string $char
     * @param string $functionName
     *
     * @dataProvider arrowToFunctionDataProvider
     */
    public function testArrow(string $char, string $functionName): void
    {
        /**
         * @var \MarcinJozwikowski\ConsoleDatePicker\Picker\DatePicker $picker
         * @var DatePickerDisplayInterface|\PHPUnit\Framework\MockObject\MockObject $pickerDisplay
         */
        [$picker, $pickerDisplay] = $this->getPickerMocks("\033[" . $char . "\n");
        $pickerDisplay->expects($this->once())->method($functionName);
        $picker->getDate($pickerDisplay, '');
    }

    private function getInput(string $streamValue): StreamableInputInterface
    {
        $stream = fopen('php://memory', 'wb+');
        fwrite($stream, $streamValue);
        rewind($stream);

        $input = $this->createMock(StreamableInputInterface::class);
        $input->method('getStream')->withAnyParameters()->willReturn($stream);

        return $input;
    }

    private function getOutput(): OutputInterface
    {
        $output = $this->createMock(OutputInterface::class);
        $output->method('write')->willReturn(null);

        return $output;
    }

    private function getPickerMocks(string $inputString): array
    {
        $input     = $this->getInput($inputString);
        $output    = $this->getOutput();
        $startDate = new DateTime();
        $picker    = new DatePicker($input, $output);

        $pickerDisplay = $this->createMock(DatePickerDisplayInterface::class);
        $pickerDisplay->expects($this->once())->method('getValue')->willReturn($startDate);

        return [$picker, $pickerDisplay];
    }

    public function arrowToFunctionDataProvider(): array
    {
        return [
            ["A", "addToCurrentField"],
            ["B", "subtractFromCurrentField"],
            ["C", "nextField"],
            ["D", "previousField"],
        ];
    }
}
