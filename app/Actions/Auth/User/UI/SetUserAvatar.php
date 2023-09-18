<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

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
                        'customer_id' => customer()->id
                    ]
                )
                ->usingName($user->username."-avatar")
                ->usingFileName($user->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $user->avatar_id = $avatarID;
            $user->saveQuietly();
        } catch (Exception) {
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
            $this->handle($user);
        }


        return 0;
    }
}
