<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
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
        $loggedIn = $request->get('logged_in');
        $origin   = $referrer ? preg_replace('/^(https?:\/\/)?(www\.)?([^\/]+).*/', '$3', $referrer) : null;

        $loggedInState = match (true) {
            is_null($loggedIn)                                                              => null,
            filter_var($loggedIn, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true => 'login',
            default                                                                         => 'logout',
        };

        $portfolioWebsite   = PortfolioWebsite::where('url', 'LIKE', '%' . $origin . '%')->firstOrFail();
        $announcementsQuery = $portfolioWebsite->announcements()
            ->where('status', AnnouncementStatusEnum::ACTIVE->value);

        $path = $referrer ? preg_replace('/^(https?:\/\/)?(www\.)?[^\/]+(\/.*)?$/', '$3', $referrer) : null;
        $path = $path === '' ? null : $path;

        if (!is_null($loggedInState) && $path) {
            $announcementsQuery->whereJsonContains('settings->target_users->auth_state', $loggedInState);
        }

        $announcements = $announcementsQuery->get();

        $announcement = $announcements->map(function ($announcement) use ($referrer, $path, $loggedIn) {
            $targetType    = Arr::get($announcement->settings, 'target_pages.type');
            $specificPages = collect(Arr::get($announcement->settings, 'target_pages.specific', []));

            if (! blank($specificPages)) {
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
                if ($targetType === 'all' && is_null($path)) {
                    return $announcement;
                }
            }

            return null;
        });

        /** @var Announcement $announcementAll */
        $announcementAll = $announcementsQuery->whereJsonContains('settings->target_pages->type', 'all')->first();
        if ($announcementAll) {
            return $announcementAll;
        }

        return $announcement->whereNotNull()->first();
    }

    public function jsonResponse(?Announcement $announcement): JsonResponse|\stdClass
    {
        if (!$announcement) {
            return new \stdClass();
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
