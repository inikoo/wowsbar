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

/**
 * @property \Exception $exception
 */
class SetUserAvatar
{
    use AsAction;

    private Exception $exception;

    public function handle(User $user): ?User
    {
        try {
            /** @var Media $media */
            $media = $user->addMediaFromUrl("https://api.dicebear.com/7.x/identicon/svg?seed=".$user->slug)
                ->preservingOriginal()
                ->usingName($user->slug."-avatar")
                ->usingFileName($user->slug."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $user->avatar_id = $avatarID;
            $user->saveQuietly();
        } catch (Exception $e) {
            $this->exception = $e;

            return null;
        }

        return $user;
    }


    public string $commandSignature = 'user:reset-avatar {slug : User slug}';

    public function asCommand(Command $command): int
    {
        $user = User::where('slug', $command->argument('slug'))->first();

        if (!$user) {
            $command->error('User not found');

            return 1;
        }
        $res = $this->handle($user);
        if ($res) {
            $command->line('Avatar set up');
        } else {
            $command->error($this->exception->getMessage());

            return 1;
        }


        return 0;
    }
}
