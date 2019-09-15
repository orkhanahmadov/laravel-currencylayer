<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Symfony\Component\Console\Helper\Table;
use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    /**
     * Renders console output.
     *
     * @param string $title
     * @param array<mixed> $rows
     */
    protected function output(string $title, array $rows): void
    {
        $table = new Table($this->output);

        $table->setStyle('box-double')
            ->setColumnWidths([8, 16])
            ->setHeaderTitle($title)
            ->setHeaders(['Currency', 'Rate'])
            ->addRows($rows);

        $table->render();
    }

    /**
     * Prepare table rows.
     *
     * @param array<string> $currencies
     * @param array<float>|float $rates
     *
     * @return array<mixed>
     */
    protected function prepareRows(array $currencies, $rates): array
    {
        $rows = [];

        if (is_array($rates)) {
            foreach ($rates as $code => $rate) {
                $rows[] = [$code, $rate];
            }
        } else {
            $rows[] = [$currencies[0], $rates];
        }

        return $rows;
    }
}
