<?php

declare(strict_types=1);

namespace MarcinJozwikowski\ConsoleDatePicker\Display;

use DateInterval;
use DateTime;

abstract class AbstractPickerDisplay
{
    protected \DateTime $value;
    private string      $separator;
    private int         $selectedField;
    private int         $maxField;

    abstract protected function getFields(): array;

    public function __construct(?DateTime $defaultDate = null, $separator = '-', bool $startAtRight = true)
    {
        $this->value         = $defaultDate ?? new \DateTime();
        $this->separator     = $separator;
        $this->maxField      = count($this->getFields()) - 1;
        $this->selectedField = $startAtRight ? $this->maxField : 0;
    }

    public function nextField(): void
    {
        $this->selectedField++;
        if ($this->selectedField > $this->maxField) {
            $this->selectedField = 0;
        }
    }

    public function previousField(): void
    {
        $this->selectedField--;
        if ($this->selectedField < 0) {
            $this->selectedField = $this->maxField;
        }
    }

    /**
     * @throws \Exception
     */
    public function addToCurrentField(): void
    {
        $this->value->add(new DateInterval($this->getFields()[$this->selectedField]['modifier']));
    }

    /**
     * @throws \Exception
     */
    public function subtractFromCurrentField(): void
    {
        $this->value->sub(new DateInterval($this->getFields()[$this->selectedField]['modifier']));
    }

    public function getDisplayString(): string
    {
        $result = [];
        foreach ($this->getFields() as $fieldId => $field) {
            $stringValue = $this->value->format($field['value']);
            if ($fieldId === $this->selectedField) {
                $stringValue = sprintf('<info>%s</info>', $stringValue);
            }
            $result[] = $stringValue;
        }

        return implode($this->separator, $result);
    }

    public function getValue(): DateTime
    {
        return $this->value;
    }
}