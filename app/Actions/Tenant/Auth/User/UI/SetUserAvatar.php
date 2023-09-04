<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User\UI;

use App\Models\Auth\User;
use App\Models\Media\Media;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SetUserAvatar
{
    use AsAction;

    public function handle(User $user): User
    {
        try {
            $seed = $user->id;
            /** @var Media $media */
            $media = $user->addMediaFromUrl("https://avatars.dicebear.com/api/identicon/$seed.svg")
                ->preservingOriginal()
                ->withProperties(
                    [
                        'tenant_id' => app('currentTenant')->id
                    ]
                )
                ->usingName($user->username."-avatar")
                ->usingFileName($user->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $user->avatar_id = $avatarID;
            $user->saveQuietly();
        } catch(Exception) {
            //
        }
        return $user;
    }


    public string $commandSignature = 'user:reset-avatar {username : User username}';

    public function asCommand(Command $command): int
    {


        $user = User::where('username', $command->argument('username'))->first();
        if (!$user) {
            $command->error('User not found');
            return 1;
        } else {
            $user->tenant->makeCurrent();
            $this->handle($user);
        }


        return 0;
    }
}
