<?php

declare(strict_types=1);

namespace MarcinJozwikowski\ConsoleDatePicker\Display;

class YMDPickerDisplay extends AbstractPickerDisplay implements DatePickerDisplayInterface
{
    private array $fields = [
        ['value' => 'Y', 'modifier' => 'P1Y'],
        ['value' => 'm', 'modifier' => 'P1M'],
        ['value' => 'd', 'modifier' => 'P1D'],
    ];

    protected function getFields(): array
    {
        return $this->fields;
    }
}