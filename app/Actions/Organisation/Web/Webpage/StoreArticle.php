<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 10:10:39 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage;

use App\Actions\Organisation\Web\WebpageVariant\StoreWebpageVariant;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Organisation\Web\Webpage;
use App\Models\Organisation\Web\Website;
use App\Rules\CaseSensitive;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreArticle
{
    use AsAction;

    public function handle(Website $website, array $modelData, array $webpageVariantData = []): Webpage
    {
        data_set($modelData, 'level', $this->getLevel(Arr::get($modelData, 'parent_id')));

        data_set($modelData,'data',Arr::only($modelData,['title','subtitle']));

        Arr::forget($modelData,['title','subtitle']);

        /** @var Webpage $webpage */
        $webpage = $website->webpages()->create($modelData);
        $webpage->stats()->create();

        StoreWebpageVariant::run($webpage, $webpageVariantData);

        return $webpage;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("website.edit");
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
        $request->merge(
            [
                'type'    => WebpageTypeEnum::ARTICLE->value,
                'purpose' => WebpagePurposeEnum::ARTICLE->value,
                'code'=>'art-'.gmdate('Ymd')
            ]
        );
    }

    public function rules(): array
    {
        return [
            'url'     => ['required', new CaseSensitive('webpages'), 'max:255'],
            'code'    => ['required', 'unique:webpages', 'max:64'],
            'type'    => ['required', new Enum(WebpageTypeEnum::class)],
            'purpose' => ['required', new Enum(WebpagePurposeEnum::class)],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],


        ];
    }

    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $request->validate();
        $modelData = $request->validated();
        data_set($modelData, 'parent_id', $webpage->id);
        data_set($modelData, 'url', Str::lower($modelData['url']));

        return $this->handle(organisation()->website, $modelData);
    }

    public function htmlResponse(Webpage $webpage): RedirectResponse
    {
        return Redirect::route('org.website.webpages.show', [
            $webpage->slug
        ]);
    }

}
