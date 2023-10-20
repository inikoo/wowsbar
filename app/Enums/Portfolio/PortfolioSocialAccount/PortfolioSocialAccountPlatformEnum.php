<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 09:53:48 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\PortfolioSocialAccount;

use App\Enums\EnumHelperTrait;

enum PortfolioSocialAccountPlatformEnum: string
{
    use EnumHelperTrait;

    case FACEBOOK            = 'facebook';
    case INSTAGRAM           = 'instagram';
    case TIKTOK              = 'tiktok';
    case PINTEREST           = 'pinterest';
    case LINKEDIN            = 'linkedin';
    case TWITTER             = 'twitter';
    case YOUTUBE             = 'youtube';

    public static function platformIcon(): array
    {
        return [
            self::FACEBOOK->value => [

                'tooltip' => __(self::FACEBOOK->value),
                'icon'    => 'fab fa-facebook',
                'class'   => 'text-indigo-500'

            ],
            self::INSTAGRAM->value        => [

                'tooltip' => __(self::INSTAGRAM->value),
                'icon'    => 'fab fa-instagram',
                'class'   => 'text-red-500'

            ],
            self::TIKTOK->value     => [

                'tooltip' => __(self::TIKTOK->value),
                'icon'    => 'fab fa-tiktok',
                'class'   => 'text-black'

            ],
            self::PINTEREST->value     => [

                'tooltip' => __(self::PINTEREST->value),
                'icon'    => 'fab fa-pinterest',
                'class'   => 'text-red-700'

            ],
            self::LINKEDIN->value     => [

                'tooltip' => __(self::LINKEDIN->value),
                'icon'    => 'fab fa-linkedin',
                'class'   => 'text-blue-500'

            ],
            self::TWITTER->value     => [

                'tooltip' => __(self::TWITTER->value),
                'icon'    => 'fab fa-twitter',
                'class'   => 'text-sky-500'

            ],
            self::YOUTUBE->value     => [

                'tooltip' => __(self::YOUTUBE->value),
                'icon'    => 'fab fa-youtube',
                'class'   => 'text-red-500'

            ],

        ];
    }
}
