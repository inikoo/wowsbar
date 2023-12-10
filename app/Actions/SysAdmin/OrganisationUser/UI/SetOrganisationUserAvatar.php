<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser\UI;

use App\Actions\Helpers\Avatars\GetDiceBearAvatar;
use App\Enums\Helpers\Avatars\DiceBearStylesEnum;
use App\Models\Auth\OrganisationUser;
use App\Models\Media\Media;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SetOrganisationUserAvatar
{
    use AsAction;

    private Exception $exception;


    public function handle(OrganisationUser $organisationUser): ?OrganisationUser
    {
        $seed=$organisationUser->username;
        try {
            /** @var Media $media */
            $media = $organisationUser->addMediaFromString(GetDiceBearAvatar::run(DiceBearStylesEnum::IDENTICON, $seed))
                ->preservingOriginal()
                ->usingName($organisationUser->username."-avatar")
                ->usingFileName($organisationUser->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $organisationUser->update(['avatar_id' => $avatarID]);
        } catch (Exception $e) {
            $this->exception = $e;

            return null;
        }

        return $organisationUser;
    }


    public string $commandSignature = 'org-user:reset-avatar {username : User username}';

    public function asCommand(Command $command): int
    {
        $organisationUser = OrganisationUser::where('username', $command->argument('username'))->first();
        if (!$organisationUser) {
            $command->error('Organisation user not found');

            return 1;
        }
        $res = $this->handle($organisationUser);
        if ($res) {
            $command->line('Avatar set up');
        } else {
            $command->error($this->exception->getMessage());

            return 1;
        }


        return 0;
    }

}
