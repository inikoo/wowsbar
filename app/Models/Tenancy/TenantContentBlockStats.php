<?php

namespace App\Models\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tenancy\TenantContentBlockStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $number_content_blocks
 * @property int $number_content_blocks_state_in_process
 * @property int $number_content_blocks_state_ready
 * @property int $number_content_blocks_state_live
 * @property int $number_content_blocks_state_retired
 * @property int $number_content_blocks_type_product
 * @property int $number_content_blocks_type_product_state_in_process
 * @property int $number_content_blocks_type_product_state_ready
 * @property int $number_content_blocks_type_product_state_live
 * @property int $number_content_blocks_type_product_state_retired
 * @property int $number_content_blocks_type_category
 * @property int $number_content_blocks_type_category_state_in_process
 * @property int $number_content_blocks_type_category_state_ready
 * @property int $number_content_blocks_type_category_state_live
 * @property int $number_content_blocks_type_category_state_retired
 * @property int $number_content_blocks_type_basket
 * @property int $number_content_blocks_type_basket_state_in_process
 * @property int $number_content_blocks_type_basket_state_ready
 * @property int $number_content_blocks_type_basket_state_live
 * @property int $number_content_blocks_type_basket_state_retired
 * @property int $number_content_blocks_type_checkout
 * @property int $number_content_blocks_type_checkout_state_in_process
 * @property int $number_content_blocks_type_checkout_state_ready
 * @property int $number_content_blocks_type_checkout_state_live
 * @property int $number_content_blocks_type_checkout_state_retired
 * @property int $number_content_blocks_type_footer
 * @property int $number_content_blocks_type_footer_state_in_process
 * @property int $number_content_blocks_type_footer_state_ready
 * @property int $number_content_blocks_type_footer_state_live
 * @property int $number_content_blocks_type_footer_state_retired
 * @property int $number_content_blocks_type_header
 * @property int $number_content_blocks_type_header_state_in_process
 * @property int $number_content_blocks_type_header_state_ready
 * @property int $number_content_blocks_type_header_state_live
 * @property int $number_content_blocks_type_header_state_retired
 * @property int $number_content_blocks_type_banner
 * @property int $number_content_blocks_type_banner_state_in_process
 * @property int $number_content_blocks_type_banner_state_ready
 * @property int $number_content_blocks_type_banner_state_live
 * @property int $number_content_blocks_type_banner_state_retired
 * @property int $number_content_blocks_type_text
 * @property int $number_content_blocks_type_text_state_in_process
 * @property int $number_content_blocks_type_text_state_ready
 * @property int $number_content_blocks_type_text_state_live
 * @property int $number_content_blocks_type_text_state_retired
 * @property int $number_content_blocks_type_picture
 * @property int $number_content_blocks_type_picture_state_in_process
 * @property int $number_content_blocks_type_picture_state_ready
 * @property int $number_content_blocks_type_picture_state_live
 * @property int $number_content_blocks_type_picture_state_retired
 * @property int $number_content_blocks_type_maps
 * @property int $number_content_blocks_type_maps_state_in_process
 * @property int $number_content_blocks_type_maps_state_ready
 * @property int $number_content_blocks_type_maps_state_live
 * @property int $number_content_blocks_type_maps_state_retired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|TenantContentBlockStats newModelQuery()
 * @method static Builder|TenantContentBlockStats newQuery()
 * @method static Builder|TenantContentBlockStats query()
 * @method static Builder|TenantContentBlockStats whereCreatedAt($value)
 * @method static Builder|TenantContentBlockStats whereId($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocks($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBanner($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBannerStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBannerStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBannerStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBannerStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBasket($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBasketStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBasketStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBasketStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeBasketStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCategory($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCategoryStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCategoryStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCategoryStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCategoryStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCheckout($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCheckoutStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCheckoutStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCheckoutStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeCheckoutStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeFooter($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeFooterStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeFooterStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeFooterStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeFooterStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeHeader($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeHeaderStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeHeaderStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeHeaderStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeHeaderStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeMaps($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeMapsStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeMapsStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeMapsStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeMapsStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypePicture($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypePictureStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypePictureStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypePictureStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypePictureStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeProduct($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeProductStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeProductStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeProductStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeProductStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeText($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeTextStateInProcess($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeTextStateLive($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeTextStateReady($value)
 * @method static Builder|TenantContentBlockStats whereNumberContentBlocksTypeTextStateRetired($value)
 * @method static Builder|TenantContentBlockStats whereTenantId($value)
 * @method static Builder|TenantContentBlockStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TenantContentBlockStats extends Model
{
    use HasFactory;

    protected $guarded = [];
}
