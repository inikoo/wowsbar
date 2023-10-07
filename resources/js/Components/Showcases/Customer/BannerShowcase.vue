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


import { faRectangleWide, faGlobe } from "../../../../private/pro-light-svg-icons"
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { cloneDeep } from 'lodash'

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
    <!-- <pre>{{ data.compiled_layout }}</pre> -->
    <Slider v-if="data.compiled_layout?.components?.length" :data="data.compiled_layout" />
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
    <div v-if="data.state !== 'unpublished'" :class="['p-2.5', !data.compiled_layout?.components?.length ?  'flex justify-center' : '' ]">
        <Input :fieldData="{ copyButton: true, readonly: true }" fieldName='url' :form="{ url: data.url }" />
    </div>
</template>

