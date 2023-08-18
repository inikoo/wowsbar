<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\RootUser\UI;

use App\Models\Auth\PublicUser;
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
                        'tenant_id' => app('currentTenant')
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
