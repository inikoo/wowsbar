<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Mon, 12 Jun 2023 16:00:25 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\History;

use App\Actions\Tenant\Auth\User\Traits\WithFormattedUserHistories;
use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ShowHistories
{
    use AsAction;
    use WithAttributes;
    use WithFormattedUserHistories;

    public function handle($model): LengthAwarePaginator|array|bool
    {
        return $model->audits()->paginate();
    }
}
