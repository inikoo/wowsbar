<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Currency\Exchange\ExchangeRate;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class PairCurrencyViaExchangeRateApi
{
    use AsAction;

    public function handle(string $baseCurrency, string $targetCurrency, int|null $amount = null): string
    {
        $apiKeys = explode(',', env('CURRENCY_EXCHANGE_API_KEY'));
        $baseUrl = env('CURRENCY_EXCHANGE_API_URL') . '/' . Arr::shuffle($apiKeys)[0] . '/pair/' . $baseCurrency . '/' . $targetCurrency;

        if($amount) {
            $baseUrl .= '/' . $amount;
        }

        $result = Http::get($baseUrl);

        return $result['conversion_result'];
    }
}
