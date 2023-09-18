<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\PublicUser\UI;

use App\Models\CRM\PublicUser;
use App\Models\Media\Media;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SetRootUserAvatar
{
    use AsAction;

    public function handle(PublicUser $rootUser): PublicUser
    {
        try {
            $seed = $rootUser->id;
            /** @var Media $media */
            $media = $rootUser->addMediaFromUrl("https://avatars.dicebear.com/api/identicon/$seed.svg")
                ->preservingOriginal()
                ->withProperties(
                    [
                        'tenant_id' => customer()
                    ]
                )
                ->usingFileName($rootUser->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $rootUser->update(['avatar_id' => $avatarID]);
        } catch(Exception) {
            //
        }
        return $rootUser;
    }


    public string $commandSignature = 'user:reset-avatar {username : User username}';

    public function asCommand(Command $command): int
    {


        $rootUser = PublicUser::where('username', $command->argument('username'))->first();
        if (!$rootUser) {
            $command->error('User not found');
            return 1;
        } else {
            $rootUser->tenant->makeCurrent();
            $this->handle($rootUser);
        }


        return 0;
    }
}
