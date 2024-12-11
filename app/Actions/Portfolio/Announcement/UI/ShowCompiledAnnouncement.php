<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ShowCompiledAnnouncement
{
    use AsController;

    public function handle(PortfolioWebsite $portfolioWebsite, ?string $targetPage, string $targetUser): ?Announcement
    {
        $announcements = GetActiveAnnouncement::run($portfolioWebsite);

        foreach ($announcements as $announcement) {
            $selectedAnnouncement = $announcement
                ->where(function ($query) use ($targetPage, $portfolioWebsite) {
                    $query->where('status', AnnouncementStatusEnum::ACTIVE->value)
                    ->where('portfolio_website_id', $portfolioWebsite->id)
                    ->where(function ($subQuery) use ($targetPage) {
                        $subQuery->whereRaw("
                      EXISTS (
                          SELECT 1
                          FROM jsonb_array_elements(published_settings->'target_pages'->'specific') AS specific
                          WHERE specific->>'url' = ?
                      )
                  ", [$targetPage])
                            ->orWhere('published_settings->target_pages->type', 'all');
                    });
                })->first();

            if ($selectedAnnouncement && Arr::get($selectedAnnouncement->published_settings, 'target_users')['auth_state'] != 'all') {
                $selectedAnnouncement = $selectedAnnouncement->whereJsonContains('published_settings->target_users->auth_state', $targetUser)->first();
            }

            return $selectedAnnouncement;
        }

        return $announcements->first();
    }

    public function asController(ActionRequest $request): ?Announcement
    {
        $domain   = $request->get('domain');
        $loggedIn = $request->get('logged_in');
        $loggedIn = (string) $loggedIn == 'true' ? 'login' : 'logout';

        $portfolioWebsite = GetPortfolioWebsiteFromDomain::run($domain);
        $targetPath       = GetSelectedPathFromDomain::run($domain);

        return $this->handle($portfolioWebsite, $targetPath, $loggedIn);
    }

    public function jsonResponse(?Announcement $announcement): JsonResponse|\stdClass
    {
        if (!$announcement) {
            return new \stdClass();
        }

        return response()->json([
            'ulid'                 => $announcement->ulid,
            'fields'               => $announcement->published_fields,
            'compiled_layout'      => $announcement->compiled_layout,
            'container_properties' => $announcement->container_properties,
            'restrictions'         => $this->hasRestrictions($announcement->published_settings)
        ]);
    }

    public function htmlResponse(?Announcement $announcement)
    {
        return Inertia::render(
            'DeliverAnnouncement',
            [
                'announcement_data' => $announcement
            ]
        );
    }

    public function hasRestrictions(array $data): bool
    {
        $targetPages = Arr::get($data, 'target_pages', []);
        $targetUsers = Arr::get($data, 'target_users', []);

        if ($targetPages['type'] === 'specific' || $targetUsers['auth_state'] === 'specific') {
            return true;
        }

        return false;
    }
}
