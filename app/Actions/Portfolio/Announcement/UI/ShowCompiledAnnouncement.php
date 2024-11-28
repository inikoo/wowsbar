<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Inertia\Inertia;

class ShowCompiledAnnouncement
{
    use AsController;

    public function handle(ActionRequest $request): ?Announcement
    {
        $referrer = $request->get('domain');
        $origin = $referrer ? preg_replace('/^www\./', '', parse_url($referrer, PHP_URL_HOST)) : null;

        $portfolioWebsite = PortfolioWebsite::where('url', 'LIKE', '%' . $origin . '%')->firstOrFail();
        $announcements = $portfolioWebsite->announcements;

        $originPath = $referrer ? parse_url($referrer, PHP_URL_PATH) : null;

        $announcement = $announcements->map(function ($announcement) use ($referrer, $originPath) {
            $targetType = Arr::get($announcement->settings, 'target_pages.type');

            if ($targetType === 'all') {
                return $announcement;
            }

            $specificPages = collect(Arr::get($announcement->settings, 'target_pages.specific', []));

            $matchingPage = $specificPages->first(function ($page) use ($originPath) {
                return match ($page['when']) {
                    'contain' => str_contains($originPath, $page['url']),
                    'exact' => $originPath === $page['url'],
                    default => false,
                };
            });

            if ($matchingPage) {
                return $announcement;
            }

            return null;
        });

        return $announcement->first();
    }

    public function jsonResponse(?Announcement $announcement): ?JsonResponse
    {
        if(!$announcement) {
            return null;
        }

        return response()->json([
            'ulid'              => $announcement->ulid,
            'fields'            => $announcement->fields,
            'compiled_layout' => $announcement->compiled_layout,
            'container_properties' => $announcement->container_properties,
            'restrictions' => $this->hasRestrictions($announcement->settings)
        ]);
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
