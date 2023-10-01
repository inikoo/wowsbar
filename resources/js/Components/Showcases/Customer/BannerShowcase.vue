<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:23:49 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import Input from '@/Components/Forms/Fields/Input.vue'
import Slider from "@/Components/Slider/Slider.vue"
import { trans } from "laravel-vue-i18n"


import { faWindowMaximize, faGlobe } from "../../../../private/pro-light-svg-icons"
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { cloneDeep } from 'lodash'

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
    banner: {
        'slug': string,
        'ulid': string,
        'id': number,
        'code': string,
        'name': string,
        'state' : String
    }
}>()

onMounted(() => {
    props.data.banner.components = cloneDeep(props.data.banner.components).filter(item => item.visibility === true)
})

</script>


<template>
    <Slider v-if="data.banner?.components?.length" :data="data.banner" />
    <EmptyState v-else :data="{
        title: trans('You don\'t have slides to show'),
        description: trans('Create new slides in the workshop to get started'),
        action: {
            label: trans('Workshop'),
            route: props.pageHead.route,
            tooltip: trans('Workshop')
        }
    }" />
    <div v-if="banner.state !== 'unpublished'" :class="['p-2.5', !data.banner?.components?.length ?  'flex justify-center' : '' ]">
        <Input :fieldData="{ copyButton: true, readonly: true }" fieldName='url' :form="{ url: data.url }" />
    </div>
</template>

