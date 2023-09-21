<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Models\Auth\OrganisationUser;
use App\Models\Media\Media;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SetOrganisationUserAvatar
{
    use AsAction;

    public function handle(OrganisationUser $organisationUser): OrganisationUser
    {
        try {
            $seed = 'org-'.$organisationUser->id;
            /** @var Media $media */
            $media = $organisationUser->addMediaFromUrl("https://avatars.dicebear.com/api/identicon/$seed.svg")
                ->preservingOriginal()
                ->usingName($organisationUser->username."-avatar")
                ->usingFileName($organisationUser->username."-avatar.sgv")
                ->toMediaCollection('avatar');

            $avatarID = $media->id;

            $organisationUser->update(['avatar_id' => $avatarID]);
        } catch(Exception) {
            //
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
        } else {
            $this->handle($organisationUser);
        }

        return 0;
    }
}
