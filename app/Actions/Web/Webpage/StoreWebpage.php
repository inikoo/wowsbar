<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Helpers\Snapshot\StoreWebpageSnapshot;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebpage
{
    use AsAction;

    public function handle(Website $website, array $modelData): Webpage
    {
        data_set($modelData, 'level', $this->getLevel(Arr::get($modelData, 'parent_id')));


        /** @var Webpage $webpage */
        $webpage = $website->webpages()->create($modelData);

        $snapshot = StoreWebpageSnapshot::run(
            $webpage,
            [
                'layout' => [
                    'src'  => null,
                    'html' => ''
                ]
            ],
        );

        $webpage->update(
            [
                'unpublished_snapshot_id' => $snapshot->id,
                'compiled_layout'         => $snapshot->compiledLayout(),

            ]
        );


        $webpage->stats()->create();


        return $webpage;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function getLevel($parent_id): int
    {
        /** @var Webpage $parent */
        if ($parent_id && $parent = Webpage::where('id', $parent_id)->first()) {
            return $parent->level + 1;
        }

        return 1;
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $type = WebpageTypeEnum::CONTENT->value;

        if ($_type = Arr::get($request->all(), 'type')) {
            if (is_array($_type)) {
                $type = $_type['value'];
            } else {
                $type = $_type;
            }
        }


        $purpose = match ($type) {
            WebpageTypeEnum::SMALL_PRINT->value => WebpagePurposeEnum::OTHER_SMALL_PRINT->value,
            WebpageTypeEnum::SHOP->value        => WebpagePurposeEnum::SHOP->value,
            default                             => WebpagePurposeEnum::CONTENT->value
        };

        $request->merge(
            [
                'type'    => $type,
                'purpose' => $purpose
            ]
        );
    }

    public function rules(): array
    {
        return [
            'url'     => ['required', 'iunique:webpages', 'max:255', 'alpha_dash:ascii'],
            'code'    => ['required', 'unique:webpages', 'max:64', 'alpha_dash:ascii',
                          Rule::notIn(
                              [
                                  'websites', 'create','edit','workshop'
                              ]
                          )],
            'type'    => ['required', new Enum(WebpageTypeEnum::class)],
            'purpose' => ['required', new Enum(WebpagePurposeEnum::class)]


        ];
    }

    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $request->validate();
        $modelData = $request->validated();
        data_set($modelData, 'parent_id', $webpage->id);
        data_set($modelData, 'url', Str::lower($modelData['url']));

        return $this->handle($webpage->website, $modelData);
    }

    public function htmlResponse(Webpage $webpage): RedirectResponse
    {
        return Redirect::route('org.websites.show.webpages.show', [
            $webpage->website->slug,
            $webpage->slug
        ]);
    }

}
