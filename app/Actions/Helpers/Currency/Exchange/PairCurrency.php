<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Currency\Exchange;

use App\Actions\Helpers\Currency\Exchange\ExchangeRate\PairCurrencyViaExchangeRateApi;
use App\Actions\Helpers\Currency\Exchange\OpenExchange\PairCurrencyViaOpenExchangeApi;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class PairCurrency
{
    use AsAction;
    use AsCommand;

    public string $commandSignature = 'exchange:pair {baseCurrency} {targetCurrency} {amount?}';

    public function handle(string $baseCurrency, string $targetCurrency, int|null $amount = null): string
    {
        $baseMethods = [
            // PairCurrencyViaOpenExchangeApi::class, Cannot be used because of the account limitations
            PairCurrencyViaExchangeRateApi::class
        ];

        return Arr::shuffle($baseMethods)[0]::run($baseCurrency, $targetCurrency, $amount);
    }

    public function asCommand(Command $command): void
    {
        $result = $this->handle($command->argument('baseCurrency'), $command->argument('targetCurrency'), $command->argument('amount'));
        echo $result;
    }
}
