<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Web\WebpageVariant\StoreWebpageVariant;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
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

        data_set($modelData, 'data', Arr::only($modelData, ['title', 'subtitle']));

        Arr::forget($modelData, ['title', 'subtitle']);

        /** @var Webpage $webpage */
        $webpage = $website->webpages()->create($modelData);
        $webpage->stats()->create();

        StoreWebpageVariant::run($webpage, $webpageVariantData);

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
        $request->merge(
            [
                'type'    => WebpageTypeEnum::BLOG->value,
                'purpose' => WebpagePurposeEnum::ARTICLE->value,
                'code'    => 'art-'.gmdate('Ymd')
            ]
        );
    }

    public function rules(): array
    {
        return [
            'url'      => ['required', 'iunique:webpages', 'max:255'],
            'code'     => ['required', 'unique:webpages', 'max:64'],
            'type'     => ['required', new Enum(WebpageTypeEnum::class)],
            'purpose'  => ['required', new Enum(WebpagePurposeEnum::class)],
            'title'    => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],


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
