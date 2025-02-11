<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 21 Mar 2023 19:17:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Media;

use App\Helpers\ImgProxy\Image;
use App\Models\Traits\IsMedia;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * App\Models\Media\Media
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string $slug
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string|null $scope
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property string|null $checksum
 * @property bool $is_animated
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @property-read mixed $original_url
 * @property-read mixed $preview_url
 * @property-read mixed $type
 * @method static MediaCollection<int, static> all($columns = ['*'])
 * @method static MediaCollection<int, static> get($columns = ['*'])
 * @method static Builder|Media newModelQuery()
 * @method static Builder|Media newQuery()
 * @method static Builder|Media ordered()
 * @method static Builder|Media query()
 * @method static Builder|Media whereChecksum($value)
 * @method static Builder|Media whereCollectionName($value)
 * @method static Builder|Media whereConversionsDisk($value)
 * @method static Builder|Media whereCreatedAt($value)
 * @method static Builder|Media whereCustomProperties($value)
 * @method static Builder|Media whereCustomerId($value)
 * @method static Builder|Media whereDisk($value)
 * @method static Builder|Media whereFileName($value)
 * @method static Builder|Media whereGeneratedConversions($value)
 * @method static Builder|Media whereId($value)
 * @method static Builder|Media whereIsAnimated($value)
 * @method static Builder|Media whereManipulations($value)
 * @method static Builder|Media whereMimeType($value)
 * @method static Builder|Media whereModelId($value)
 * @method static Builder|Media whereModelType($value)
 * @method static Builder|Media whereName($value)
 * @method static Builder|Media whereOrderColumn($value)
 * @method static Builder|Media whereResponsiveImages($value)
 * @method static Builder|Media whereScope($value)
 * @method static Builder|Media whereSize($value)
 * @method static Builder|Media whereSlug($value)
 * @method static Builder|Media whereUpdatedAt($value)
 * @method static Builder|Media whereUuid($value)
 * @mixin \Eloquent
 */
class Media extends BaseMedia
{
    use IsMedia;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function getImage(): Image
    {
        return (new Image())->make($this->getImgProxyFilename(), $this->is_animated);
    }

}
