<?php

namespace App\Actions\Portfolio\Announcement\UI;

use Lorisleiva\Actions\Concerns\AsController;

class DeliverAnnouncement
{
    use AsController;

    public function handle(string $ulid): null
    {
        return null;

    }


    public function htmlResponse($data)
    {
        return view('announcement', ['data' => $data]);
    }

}
