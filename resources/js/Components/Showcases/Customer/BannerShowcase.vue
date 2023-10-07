<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:23:49 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { onMounted } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import Input from '@/Components/Forms/Fields/Input.vue'
import Slider from "@/Components/Slider/Slider.vue"
import { trans } from "laravel-vue-i18n"
import Image from '@/Components/Image.vue'

import { faRectangleWide, faGlobe } from "../../../../private/pro-light-svg-icons"
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { cloneDeep } from 'lodash'
import { useRangeFromNow } from '@/Composables/useFormatTime'

library.add(faRectangleWide, faGlobe)

const props = defineProps<{
    data: any
    tab?: string
}>()

onMounted(() => {
    props.data.compiled_layout.components = cloneDeep(props.data.compiled_layout.components).filter(item => item.visibility === true)
})

</script>


<template>
    <!-- <pre>{{ data }}</pre> -->
    <div class="py-3 px-5 space-y-4">
        <div v-if="data.compiled_layout?.components?.length" class="rounded-md overflow-hidden border border-gray-300 shadow">
            <div class="w-full bg-white flex items-center justify-between py-3 px-4">
                <div class="flex gap-x-2">
                    <div class="h-5 aspect-square rounded-full overflow-hidden ring-1 ring-gray-300">
                        <Image :src="data.published_snapshot.publisher_avatar" />
                    </div>
                    <div class="font-bold text-lg leading-none">{{ data.published_snapshot.publisher }}</div>
                    <div v-if="data.published_snapshot.comment" class="text-sm text-gray-500 italic">
                        ({{ data.published_snapshot.comment }})
                    </div>
                </div>
                <div class="text-sm text-gray-600 tracking-wide text-right">Published at <span class="font-bold">{{ useRangeFromNow(data.published_snapshot.published_at) }}</span> ago</div>
            </div>
            <Slider :data="data.compiled_layout" />
        </div>

        <EmptyState v-else :data="{
            title: trans('You don\'t have slides to show'),
            description: trans('Create new slides in the workshop to get started'),
            action: {
                label: trans('Workshop'),
                route: props.data.workshopRoute,
                tooltip: trans('Workshop'),
                icon: 'fal fa-drafting-compass'
            }
        }" />

        <div v-if="data.state !== 'unpublished'" class="" :class="[!data.compiled_layout?.components?.length ?  'flex justify-center' : '' ]">
            <!-- <Input :fieldData="{ copyButton: true, readonly: true }" fieldName='url' :form="{ url: data.delivery_url }" /> -->
            <p class="bg-slate-700 text-gray-100 py-2 px-2 md:px-8 rounded-md md:w-fit text-xxs md:text-base">
                {{ data.delivery_url }}
            </p>
        </div>
    </div>
    
</template>

