<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Currency\Exchange\OpenExchange;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class PairCurrencyViaOpenExchangeApi
{
    use AsAction;

    public function handle(string $baseCurrency, string $targetCurrency, int|null $amount = null): string
    {
        $baseUrl = env('CURRENCY_OPENEXCHANGE_API_URL') . '/latest.json?base=SGD';
        $apiKeys = explode(',', env('CURRENCY_OPENEXCHANGE_API_KEY'));

        $result = Http::get($baseUrl, [
            'app_id' => Arr::shuffle($apiKeys)[0]
        ]);

        echo $result . '\n';
        $rate = $result = Arr::get($result['rates'], $targetCurrency);


        if($amount) {
            $result = $rate * $amount;
        }

        return $result . '-open';
    }
}
