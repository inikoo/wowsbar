<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 12:42:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Tag;

use App\Actions\Leads\Prospect\Tags\Hydrators\TagHydrateUniversalSearch;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCrmTags;
use App\Http\Resources\Tag\TagResource;
use App\Models\Helpers\Tag;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTag
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;
    private Shop $parent;


    public function handle(array $modelData): Tag
    {
        /** @var Tag $tag */
        $tag=  Tag::findOrCreate($modelData['name'], $modelData['type']);
        $tag->update(
            [
                'label'=> $tag->name
            ]
        );
        $tag->generateTagSlug();
        $tag->saveQuietly();
        if($tag->type=='crm') {
            if(!$tag->crmStats) {
                $tag->crmStats()->create();
                OrganisationHydrateCrmTags::dispatch();
            }
        }
        TagHydrateUniversalSearch::dispatch($tag);

        return $tag;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'type' => ['required', 'string']
        ];
    }

    public function htmlResponse(): RedirectResponse
    {
        return redirect()->route(
            'org.crm.shop.prospects.tags.index',
            $this->parent->slug
        );
    }

    public function jsonResponse(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }


    public function inProspect(ActionRequest $request): Tag
    {
        $this->fillFromRequest($request);
        $this->fill(['type' => 'crm']);

        return $this->handle($this->validateAttributes());
    }

    public function inShop(Shop $shop, ActionRequest $request): Tag
    {
        $this->parent = $shop;
        $this->fillFromRequest($request);
        $this->fill(['type' => 'crm']);

        return $this->handle($this->validateAttributes());
    }

}
