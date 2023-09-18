<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Media;

use App\Models\Traits\IsMedia;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * App\Models\Media\LandlordMedia
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string $slug
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $uuid
 * @property string $collection_name
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
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static MediaCollection<int, static> all($columns = ['*'])
 * @method static MediaCollection<int, static> get($columns = ['*'])
 * @method static Builder|LandlordMedia newModelQuery()
 * @method static Builder|LandlordMedia newQuery()
 * @method static Builder|Media ordered()
 * @method static Builder|LandlordMedia query()
 * @method static Builder|LandlordMedia whereChecksum($value)
 * @method static Builder|LandlordMedia whereCollectionName($value)
 * @method static Builder|LandlordMedia whereConversionsDisk($value)
 * @method static Builder|LandlordMedia whereCreatedAt($value)
 * @method static Builder|LandlordMedia whereCustomProperties($value)
 * @method static Builder|LandlordMedia whereCustomerId($value)
 * @method static Builder|LandlordMedia whereDisk($value)
 * @method static Builder|LandlordMedia whereFileName($value)
 * @method static Builder|LandlordMedia whereGeneratedConversions($value)
 * @method static Builder|LandlordMedia whereId($value)
 * @method static Builder|LandlordMedia whereIsAnimated($value)
 * @method static Builder|LandlordMedia whereManipulations($value)
 * @method static Builder|LandlordMedia whereMimeType($value)
 * @method static Builder|LandlordMedia whereModelId($value)
 * @method static Builder|LandlordMedia whereModelType($value)
 * @method static Builder|LandlordMedia whereName($value)
 * @method static Builder|LandlordMedia whereOrderColumn($value)
 * @method static Builder|LandlordMedia whereResponsiveImages($value)
 * @method static Builder|LandlordMedia whereSize($value)
 * @method static Builder|LandlordMedia whereSlug($value)
 * @method static Builder|LandlordMedia whereUpdatedAt($value)
 * @method static Builder|LandlordMedia whereUuid($value)
 * @mixin \Eloquent
 */
class LandlordMedia extends BaseMedia
{
    use IsMedia;




}
