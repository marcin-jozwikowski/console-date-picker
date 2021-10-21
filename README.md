# Symfony Console DateTime picker

A DateTime picker for Symfony Console commands

## Installation

```shell
composer require marcin-jozwikowski/datetime-picker
```

## Usage

Example usage in Symfony command

```php
protected function execute(InputInterface $input, OutputInterface $output): int
{
    $datePicker = new DatePicker($input, $output);
    $date       = $datePicker->getDate(new YMDPickerDisplay(), "Please provide date ");

    $io->success($date->format('Y-m-d H:i:s'));

    return Command::SUCCESS;
}
```