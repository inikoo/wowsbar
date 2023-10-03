<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery;

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
        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function htmlResponse(): RedirectResponse
    {
        return redirect()->route('customer.portfolio.gallery');
    }

    public function asController(Media $media): bool
    {
        return $this->handle($media);
    }
}
