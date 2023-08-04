<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\RootUser\UI;

use App\Models\Auth\RootUser;
use App\Models\Auth\User;
use App\Models\Media\Media;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SetRootUserAvatar
{
    use AsAction;

    public function handle(RootUser $rootUser): RootUser
    {
        try {
            $seed = $rootUser->id;
            /** @var Media $media */
            $media = $rootUser->addMediaFromUrl("https://avatars.dicebear.com/api/identicon/$seed.svg")
                ->preservingOriginal()
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


        $rootUser = RootUser::where('username', $command->argument('username'))->first();
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
