<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 14 Jul 2023 15:19:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core'
import Input from '@/Components/Forms/Fields/Input.vue';
import Slider from "@/Components/Slider/Slider.vue"


import { faWindowMaximize, faGlobe } from "@/../private/pro-light-svg-icons"
import EmptyState from '@/Components/Utils/EmptyState.vue';

library.add(faWindowMaximize, faGlobe)

const props = defineProps<{
    data: {
        banner: {
            parameters: {}
            data: {
                delay: number,
                slides: [
                    {
                        imageSrc: string,
                        imageAlt: string,
                        link?: {
                            label: string,
                            target: string
                        }
                    }
                ]
            }
        },
        url: String
    }
    tab?: string
    pageHead: Object
}>()

</script>


<template>
    <Slider v-if="data.banner.components.length" :data="data.banner" />
    <EmptyState v-else :data="{
        title: 'You don\'t have slides to show',
        description: 'Create new slides in the workshop to get started',
        action: {
            label: 'Workshop',
            route: props.pageHead.route,
            tooltip: 'Workshop'
        }
    }" />
    <div :class="['p-2.5', !data.banner.components.length ?  'flex justify-center' : '' ]">
        <Input :fieldData="{ copyButton: true, readonly: true }" fieldName='url' :form="{ url: data.url }" />
    </div>
</template>

