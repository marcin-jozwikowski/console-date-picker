<?php

declare(strict_types=1);

namespace MarcinJozwikowski\ConsoleDatePicker\Display;

use DateTime;

interface DatePickerDisplayInterface
{
    public function nextField(): void;

    public function previousField(): void;

    public function addToCurrentField(): void;

    public function subtractFromCurrentField(): void;

    public function getDisplayString(): string;

    public function getValue(): DateTime;
}