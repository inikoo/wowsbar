<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 07 Jun 2023 01:32:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpagePurposeEnum: string
{
    use EnumHelperTrait;

    case STOREFRONT  = 'storefront';
    case APPOINTMENT = 'appointment';
    case LOGIN       = 'login';
    case REGISTER    = 'register';

    case BLOG    = 'blog';
    case ARTICLE = 'article';

    case CONTENT = 'content';

    public static function labels(): array
    {
        return [
            'storefront'  => __('storefront'),
            'appointment' => __('appointment'),
            'login'       => __('login'),
            'register'    => __('register'),
            'blog'        => __('blog'),
            'article'     => __('article'),
            'content'     => __('content'),

        ];
    }
}
