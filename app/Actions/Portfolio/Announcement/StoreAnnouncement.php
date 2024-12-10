<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement;

use App\Actions\Helpers\Snapshot\StoreAnnouncementSnapshot;
use App\Actions\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateAnnouncements;
use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Symfony\Component\HttpFoundation\Response;

class StoreAnnouncement
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    private PortfolioWebsite $parent;
    private string $scope;

    public $commandSignature = 'announcement:create {portfolioWebsite}';

    public function handle(PortfolioWebsite $parent, array $modelData): Announcement
    {
        $this->parent = $parent;

        data_set($modelData, 'ulid', Str::ulid());

        /** @var Announcement $announcement */
        $announcement = $parent->announcements()->create($modelData);

        $snapshot = StoreAnnouncementSnapshot::run(
            $announcement,
            [
                'layout' => [
                    'container_properties'  => null,
                    'fields'                => null
                ]
            ],
        );

        $announcement->update(
            [
                'unpublished_snapshot_id' => $snapshot->id
            ]
        );

        PortfolioWebsiteHydrateAnnouncements::dispatch($parent);

        return $announcement;
    }

    public function htmlResponse(Announcement $announcement): Response
    {
        return Redirect::route('customer.portfolio.websites.announcements.show', [
            'portfolioWebsite' => $announcement->portfolioWebsite->slug,
            'announcement'     => $announcement->ulid
        ]);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.edit");
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255']
        ];
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Announcement
    {
        $this->scope    = 'portfolio-website';
        $this->parent   = $portfolioWebsite;

        $request->validate();

        return $this->handle($portfolioWebsite, $request->validated());
    }

    public function asCommand(Command $command)
    {
        $customer = PortfolioWebsite::where('slug', $command->argument('portfolio-website'))->first();

        $this->handle($customer, [
            'name' => "Vika Announcement's"
        ]);
    }

    public function action(PortfolioWebsite $portfolioWebsite, array $objectData): Announcement
    {
        $this->parent   = $portfolioWebsite;
        $this->asAction = true;
        $this->setRawAttributes($objectData);

        $validatedData = $this->validateAttributes();
        return $this->handle($portfolioWebsite, $validatedData);
    }
}
