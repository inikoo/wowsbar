<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:57:48 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Auth\OrganisationUser\UI;

use App\Models\Media\Media;
use App\Models\Organisation\OrganisationUser;
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
