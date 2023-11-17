<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:47:30 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum MailshotTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE             = 'showcase';
    case RECIPIENTS           = 'recipients';
    case EMAIL                = 'email';


    case CHANGELOG = 'changelog';


    public function blueprint(): array
    {
        return match ($this) {
            MailshotTabsEnum::SHOWCASE => [
                'title' => __('mailshot'),
                'icon'  => 'fas fa-info-circle',
            ],
            MailshotTabsEnum::RECIPIENTS => [
                'title' => __('recipients'),
                'icon'  => 'fal fa-at',
            ],

            MailshotTabsEnum::EMAIL => [
                'title' => __('email'),
                'icon'  => 'fal fa-envelope-square',
            ],

            MailshotTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
