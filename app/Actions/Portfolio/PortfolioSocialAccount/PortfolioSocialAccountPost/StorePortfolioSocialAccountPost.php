<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost;

use App\Actions\Traits\WithSocialAudit;
use App\Enums\UI\Customer\PortfolioSocialAccountTabsEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccountPost;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioSocialAccountPost
{
    use AsAction;
    use WithAttributes;
    use WithSocialAudit;
    use AsCommand;

    private bool $asAction          = false;
    public string $commandSignature = 'post:create {account} {type}';

    public function handle(PortfolioSocialAccount $portfolioSocialAccount, array $modelData): Model
    {
        return $portfolioSocialAccount->posts()->create($modelData);
    }

    public function htmlResponse(PortfolioSocialAccountPost $post): RedirectResponse
    {
        return redirect()->route('customer.portfolio.social-accounts.show', [
            'portfolioSocialAccount' => $post->platform->slug,
            'tab'                    => PortfolioSocialAccountTabsEnum::POST->value
        ]);
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
            'task_name'   => ['required', 'string', 'max:255'],
            'start_at'    => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'notes'       => ['nullable', 'string']
        ];
    }

    public function asController(PortfolioSocialAccount $portfolioSocialAccount, ActionRequest $request): Model
    {
        $request->validate();

        return $this->handle($portfolioSocialAccount, $request->validated());
    }

    public function action(PortfolioSocialAccount $portfolioSocialAccount, array $objectData): Model
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($portfolioSocialAccount, $validatedData);
    }

    public function asCommand(Command $command): int
    {
        $portfolioSocialAccount = PortfolioSocialAccount::where('slug', $command->argument('account'))->first();

        $this->handle($portfolioSocialAccount, [
            'task_name' => fake()->title,
            'start_at'  => now(),
            'end_at'    => now()->addDays(5),
            'type'      => $command->argument('type'),
            'duration'  => 5
        ]);

        echo "Damnn u create a post 🤖" . "\n";

        return 0;
    }

}
