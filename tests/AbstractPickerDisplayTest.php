<?php

declare(strict_types=1);

use MarcinJozwikowski\ConsoleDatePicker\Display\AbstractPickerDisplay;
use MarcinJozwikowski\ConsoleDatePicker\Display\DatePickerDisplayInterface;
use PHPUnit\Framework\TestCase;

class AbstractPickerDisplayTest extends TestCase
{
    public function testNextFieldOnRightmostPosition(): void
    {
        $picker = $this->getPickerClass(new DateTime(), '-', true);

        $field = $picker->getCurrentField();
        $this->assertEquals(2, $field);

        $picker->nextField();

        $field = $picker->getCurrentField();
        $this->assertEquals(0, $field);
    }

    public function testNextFieldOnLeftmostPosition(): void
    {
        $picker = $this->getPickerClass(new DateTime(), '-', false);

        $field = $picker->getCurrentField();
        $this->assertEquals(0, $field);

        $picker->nextField();

        $field = $picker->getCurrentField();
        $this->assertEquals(1, $field);
    }

    public function testPreviousFieldOnRightmostPosition(): void
    {
        $picker = $this->getPickerClass(new DateTime(), '-', true);

        $field = $picker->getCurrentField();
        $this->assertEquals(2, $field);

        $picker->previousField();

        $field = $picker->getCurrentField();
        $this->assertEquals(1, $field);
    }

    public function testPreviousFieldOnLeftmostPosition(): void
    {
        $picker = $this->getPickerClass(new DateTime(), '-', false);

        $field = $picker->getCurrentField();
        $this->assertEquals(0, $field);

        $picker->previousField();

        $field = $picker->getCurrentField();
        $this->assertEquals(2, $field);
    }

    public function testGetDisplayStringOnRightmostPosition(): void
    {
        $date   = new DateTime();
        $picker = $this->getPickerClass($date, '-');
        $this->assertEquals($date->format('Y-m-<\i\n\f\o>d</\i\n\f\o>'), $picker->getDisplayString());
    }

    public function testGetDisplayStringOnRightmostPositionWithChangedSeparator(): void
    {
        $date   = new DateTime();
        $picker = $this->getPickerClass($date, ' ');
        $this->assertEquals($date->format('Y m <\i\n\f\o>d</\i\n\f\o>'), $picker->getDisplayString());
    }

    public function testGetDisplayStringOnLeftmostPosition(): void
    {
        $date   = new DateTime();
        $picker = $this->getPickerClass($date, '-', false);
        $this->assertEquals($date->format('<\i\n\f\o>Y</\i\n\f\o>-m-d'), $picker->getDisplayString());
    }

    public function testAddingToCurrentField(): void
    {
        $date   = new DateTime();
        $picker = $this->getPickerClass(clone $date);
        $picker->addToCurrentField();
        $diff = $date->diff($picker->getValue());
        $this->assertEquals(1, $diff->days);
        $this->assertEquals(0, $diff->invert);
    }

    public function testSubtractFromCurrentField(): void
    {
        $date   = new DateTime();
        $picker = $this->getPickerClass(clone $date);
        $picker->subtractFromCurrentField();
        $diff = $date->diff($picker->getValue());
        $this->assertEquals(1, $diff->days);
        $this->assertEquals(1, $diff->invert);
    }

    private function getPickerClass(DateTime $startDate, string $separator = '', bool $startRight = true)
    {
        return new class($startDate, $separator, $startRight) extends AbstractPickerDisplay implements DatePickerDisplayInterface {
            public function getCurrentField(): int
            {
                return $this->selectedField;
            }

            protected function getFields(): array
            {
                return [
                    ['value' => 'Y', 'modifier' => 'P1Y'],
                    ['value' => 'm', 'modifier' => 'P1M'],
                    ['value' => 'd', 'modifier' => 'P1D'],
                ];
            }
        };
    }
}
