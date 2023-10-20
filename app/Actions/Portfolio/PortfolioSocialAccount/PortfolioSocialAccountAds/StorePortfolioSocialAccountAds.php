<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountAds;

use App\Actions\Traits\WithSocialAudit;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use App\Enums\UI\Customer\PortfolioSocialAccountTabsEnum;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioSocialAccountPost;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioSocialAccountAds
{
    use AsAction;
    use WithAttributes;
    use WithSocialAudit;
    use AsCommand;

    private bool $asAction = false;
    public string $commandSignature = 'ads:create {account}';

    public function handle(PortfolioSocialAccount $portfolioSocialAccount, array $modelData): Model
    {
        data_set($modelData, 'type', PortfolioSocialAccountPostTypeEnum::ADS->value);
        data_set($modelData, 'duration', Carbon::make($modelData['start_at'])->diffInDays($modelData['end_at']));

        return $portfolioSocialAccount->posts()->create($modelData);
    }

    public function htmlResponse(PortfolioSocialAccountPost $post): RedirectResponse
    {
        return redirect()->route('customer.portfolio.social-accounts.show', [
            'portfolioSocialAccount' => $post->platform->slug,
            'tab' => PortfolioSocialAccountTabsEnum::ADS->value
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
            'task_name' => ['required', 'string', 'max:255'],
            'start_at' => ['required', 'string'],
            'end_at' => ['required', 'string']
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
            'start_at' => now(),
            'end_at' => now()->addDays(5),
            'type' => PortfolioSocialAccountPostTypeEnum::ADS,
            'duration' => 5
        ]);

        echo "Damnn u create a ads 🤖" . "\n";

        return 0;
    }

}
