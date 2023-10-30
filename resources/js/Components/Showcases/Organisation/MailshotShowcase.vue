<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import BannerPreview from '@/Components/Banner/BannerPreview.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSign, faGlobe } from '@fal/'
import { faLink } from '@far/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useCopyText } from '@/Composables/useCopyText'

library.add(faSign, faGlobe, faLink)

const props = defineProps<{
    data: any
    tab?: string
}>()


</script>


<template>
    <div class="py-3 mx-auto px-5 space-y-4">

        <div v-if="data.compiled_layout?.components?.length" class="mx-auto w-fit rounded-md overflow-hidden border border-gray-300 shadow">
            <BannerPreview :data="data" />
        </div>

        <div v-if="data.state !== 'unpublished'" class="" :class="[!data.compiled_layout?.components?.length ?  'flex justify-center' : '' ]">
            <div class="bg-white border border-gray-300 flex items-center justify-between mx-auto gap-x-3 rounded-md md:w-fit ">
                <a :href="data.delivery_url" target="_blank" class="pl-4 md:pl-5 inline-block py-2 text-xxs md:text-base text-gray-400">{{ data.delivery_url }}</a>
                <Button :style="'secondary'" class="" size="xl" @click="useCopyText(data.delivery_url)" title="Copy url to clipboard">
                    <FontAwesomeIcon icon='far fa-link' class='text-gray-500' aria-hidden='true' />
                </Button>
            </div>
        </div>
    </div>

</template>

