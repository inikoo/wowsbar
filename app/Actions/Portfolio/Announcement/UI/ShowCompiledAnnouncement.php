<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ShowCompiledAnnouncement
{
    use AsController;

    public function handle(ActionRequest $request): ?Announcement
    {
        $referrer = $request->get('domain');
        $origin   = $referrer ? preg_replace('/^(https?:\/\/)?(www\.)?([^\/]+).*/', '$3', $referrer) : null;

        $portfolioWebsite = PortfolioWebsite::where('url', 'LIKE', '%' . $origin . '%')->firstOrFail();
        $announcements    = $portfolioWebsite->announcements;

        $path = $referrer ? preg_replace('/^(https?:\/\/)?(www\.)?[^\/]+(\/.*)?$/', '$3', $referrer) : null;
        $path = $path === '' ? null : $path;

        $announcement = $announcements->map(function ($announcement) use ($referrer, $path) {
            $targetType = Arr::get($announcement->settings, 'target_pages.type');
            $specificPages = collect(Arr::get($announcement->settings, 'target_pages.specific', []));

            if(! blank($specificPages)) {
                $matchingPage = $specificPages->first(function ($page) use ($path) {
                    return match ($page['when']) {
                        'contain' => str_contains($path, $page['url']),
                        'exact'   => $path === $page['url'],
                        default   => false,
                    };
                });

                if ($matchingPage) {
                    return $announcement;
                }
            } else {
                if ($targetType === 'all') {
                    return $announcement;
                }
            }

            return null;
        });

        return $announcement->whereNotNull()->first();
    }

    public function jsonResponse(?Announcement $announcement): ?JsonResponse
    {
        if (!$announcement) {
            return null;
        }

        return response()->json([
            'ulid'                 => $announcement->ulid,
            'fields'               => $announcement->fields,
            'compiled_layout'      => $announcement->compiled_layout,
            'container_properties' => $announcement->container_properties,
            'restrictions'         => $this->hasRestrictions($announcement->settings)
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
