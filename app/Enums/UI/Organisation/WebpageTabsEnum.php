<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Wed, 12 Apr 2023 13:50:04 Central European Summer Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Enums\UI\Organisation;

use App\Enums\EnumHelperTrait;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Web\Webpage;

enum WebpageTabsEnum: string
{
    use EnumHelperTrait;

    case SHOWCASE = 'showcase';

    case ANALYTICS = 'analytics';

    case WEBPAGES = 'webpages';
    case SNAPSHOTS = 'snapshots';


    case CHANGELOG = 'changelog';

    case DATA = 'data';


    public static function navigation(Webpage $webpage): array
    {
        return collect(self::cases())
            ->filter(
                function ($case) use ($webpage) {
                    if($case==WebpageTabsEnum::WEBPAGES &&
                        (
                            $webpage->type   ==WebpageTypeEnum::AUTH       ||
                            $webpage->type   ==WebpageTypeEnum::ENGAGEMENT ||
                            $webpage->purpose==WebpagePurposeEnum::ARTICLE
                        )
                    ) {
                        return false;
                    }
                    return true;
                }
            )
            ->mapWithKeys(function ($case) use ($webpage) {
                $blueprint = $case->blueprint();
                if ($webpage->purpose == WebpagePurposeEnum::BLOG) {
                    if ($case == WebpageTabsEnum::WEBPAGES) {
                        $blueprint['title'] = __('Articles');
                    }
                }

                return [$case->value => $blueprint];
            })->all();
    }

    public function blueprint(): array
    {
        return match ($this) {
            WebpageTabsEnum::SHOWCASE => [
                'title' => __('showcase'),
                'icon'  => 'fas fa-info-circle',
            ],
            WebpageTabsEnum::ANALYTICS => [
                'title' => __('analytics'),
                'icon'  => 'fal fa-analytics',
            ],
            WebpageTabsEnum::WEBPAGES => [
                'title' => __('Webpages'),
                'icon'  => 'fal fa-level-down',
            ],
            WebpageTabsEnum::SNAPSHOTS => [
                'title' => __('Snapshots'),
                'icon'  => 'fal fa-layer-group',
            ],

            WebpageTabsEnum::DATA => [
                'title' => __('data'),
                'icon'  => 'fal fa-database',
                'type'  => 'icon',
                'align' => 'right',
            ],
            WebpageTabsEnum::CHANGELOG => [
                'title' => __('changelog'),
                'icon'  => 'fal fa-clock',
                'type'  => 'icon',
                'align' => 'right',
            ],
        };
    }
}
