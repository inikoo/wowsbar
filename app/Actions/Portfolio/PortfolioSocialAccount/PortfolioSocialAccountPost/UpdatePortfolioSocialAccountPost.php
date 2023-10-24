<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost;

use App\Actions\Traits\WithActionUpdate;
use App\Actions\Traits\WithSocialAudit;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccountPost;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdatePortfolioSocialAccountPost
{
    use AsAction;
    use WithAttributes;
    use WithSocialAudit;
    use AsCommand;
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(PortfolioSocialAccountPost $post, array $modelData): Model
    {
        return $this->update($post, $modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.social.edit");
    }

    public function rules(): array
    {
        return [
            'task_name'   => ['sometimes', 'string', 'max:255'],
            'start_at'    => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'notes'       => ['sometimes', 'string']
        ];
    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount, PortfolioSocialAccountPost $post, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($post, $request->validated());
    }
}
