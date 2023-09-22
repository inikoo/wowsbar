<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 19 Mar 2023 01:54:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum MailroomTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case SHOWCASE          = 'showcase';
    case NEWSLETTER        = 'newsletter';
    case MAILSHOT          = 'mailshot';
    case PROSPECT_CAMPAIGN = 'prospect_campaign';
    case EMAIL_TEMPLATE    = 'email_template';

    public function blueprint(): array
    {
        return match ($this) {
            MailroomTabsEnum::SHOWCASE => [
                'title' => __('showcase'),
                'icon'  => 'fas fa-info-circle',
            ],

            MailroomTabsEnum::NEWSLETTER => [
                'title' => __('newsletter'),
                'icon'  => 'fal fa-newspaper'
            ],

            MailroomTabsEnum::MAILSHOT => [
                'title' => __('mailshot'),
                'icon'  => 'fal fa-paper-plane'
            ],

            MailroomTabsEnum::PROSPECT_CAMPAIGN => [
                'title' => __('prospect campaign'),
                'icon'  => 'fal fa-ad'
            ],

            MailroomTabsEnum::EMAIL_TEMPLATE => [
                'title' => __('mail template'),
                'icon'  => 'fal fa-envelope-open-text'
            ],
        };
    }
}
