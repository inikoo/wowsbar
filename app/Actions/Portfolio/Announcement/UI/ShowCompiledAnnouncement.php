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

        $portfolioWebsite   = PortfolioWebsite::where('url', 'LIKE', '%' . $origin . '%')->firstOrFail();
        $announcement=$portfolioWebsite->announcements()->where('status', AnnouncementStatusEnum::ACTIVE->value)->first();
        return $announcement;

        $portfolioWebsite   = PortfolioWebsite::where('url', 'LIKE', '%' . $origin . '%')->firstOrFail();
        $announcementsQuery = $portfolioWebsite->announcements()
            ->where('status', AnnouncementStatusEnum::ACTIVE->value);

        $path = $referrer ? preg_replace('/^(https?:\/\/)?(www\.)?[^\/]+(\/.*)?$/', '$3', $referrer) : null;
        $path = $path === '' ? null : $path;

        $announcements = $announcementsQuery->get();
        
        $loggedInState = match (true) {
            is_null($loggedIn)                                                              => null,
            filter_var($loggedIn, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true => 'login',
            default                                                                         => 'logout',
        };

        return $announcements->map(function ($announcement) use ($referrer, $path, $loggedIn, $loggedInState) {
            $targetType    = Arr::get($announcement->settings, 'target_pages.type');
            $specificPages = collect(Arr::get($announcement->settings, 'target_pages.specific', []));
            $targetUser    = Arr::get($announcement->settings, 'target_users.auth_state');

            if ($targetUser !== "all") {
                $announcementAuth = $announcement->whereJsonContains('settings->target_users->auth_state', $loggedInState)->first();
            }

            if (! blank($specificPages)) {
                $matchingPage = $specificPages->first(function ($page) use ($path) {
                    return match ($page['when']) {
                        'contain' => str_contains($path, $page['url']),
                        'exact'   => $path === $page['url'],
                        default   => false,
                    };
                });

                if ($matchingPage) {
                    return $announcementAuth;
                }
            } else {
                $path = $path == "/" ? null : $path;
                if ($targetType === 'all' && is_null($path)) {
                    return $announcementAuth;
                }
            }
            $targetType    = Arr::get($announcement->settings, 'target_pages.type');
            if($targetType === 'specific') {   
                $announcement = $announcementAuth->whereJsonContains('settings->target_pages->specific', $loggedInState)->first();
            }

            return $announcement;
        })->whereNotNull()->first();
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
