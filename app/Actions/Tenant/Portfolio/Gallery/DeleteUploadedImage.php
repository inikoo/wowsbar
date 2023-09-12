<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:58 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Gallery;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Media\Media;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;

class DeleteUploadedImage
{
    use WithActionUpdate;

    private PortfolioWebsite|null $website = null;

    public function handle(Media $media): bool
    {
        return $media->delete();
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.edit");
    }

    public function htmlResponse(): RedirectResponse
    {
        return redirect()->route('tenant.portfolio.gallery');
    }

    public function asController(Media $media): bool
    {
        return $this->handle($media);
    }
}
