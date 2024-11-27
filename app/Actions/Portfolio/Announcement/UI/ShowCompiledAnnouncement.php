<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Models\Announcement;
use Illuminate\Support\Arr;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Inertia\Inertia;

class ShowCompiledAnnouncement
{
    use AsController;

    public function handle(Announcement $announcement, ActionRequest $request): ?Announcement
    {
        $referrer = $request->header('Referer');

        if($request->get('r')) {
            $referrer = $request->get('r');
        }

        $originPath = $referrer ? parse_url($referrer, PHP_URL_PATH) : null;

        $targetType = Arr::get($announcement->settings, 'target_pages.type');

        if(! str_contains($referrer, $announcement->portfolioWebsite->url)) {
            return null;
        }

        if($targetType === 'all') {
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

        return $matchingPage && $matchingPage['will'] === 'show' ? $announcement : null;
    }

    public function htmlResponse(?Announcement $announcement)
    {
        return $announcement?->compiled_layout;
    }
}
