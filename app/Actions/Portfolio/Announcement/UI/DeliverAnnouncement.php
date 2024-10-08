<?php

namespace App\Actions\Portfolio\Announcement\UI;

use Lorisleiva\Actions\Concerns\AsController;

class DeliverAnnouncement
{
    use AsController;

    public function handle(string $ulid): string
    {
        return 'console.log("hello")';

    }


    public function htmlResponse($data)
    {
        return response()->view('announcement', ['data' => $data])
            ->header('Content-Type', 'text/javascript');
    }

}
