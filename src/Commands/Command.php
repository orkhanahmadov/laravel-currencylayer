<?php

namespace Orkhanahmadov\LaravelCurrencylayer\Commands;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Helper\Table;

abstract class Command extends BaseCommand
{
    /**
     * Renders console output.
     *
     * @param string $title
     * @param array $rows
     */
    protected function output(string $title, array $rows): void
    {
        $table = new Table($this->output);

        $table->setStyle('box-double')
            ->setHeaderTitle($title)
            ->setHeaders(['Currency', 'Rate'])
            ->addRows($rows);

        $table->render();
    }

    /**
     * Prepare table rows.
     *
     * @param array $currencies
     * @param array|float $rates
     *
     * @return array
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
