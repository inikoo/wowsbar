<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
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
                ->usingFileName($user->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $user->update(['avatar_id' => $avatarID]);
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
