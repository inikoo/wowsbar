<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 14:45:02 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faImagePolaroid, faCloudUpload } from '../../../../private/pro-light-svg-icons'
import Tabs from "@/Components/Navigation/Tabs.vue"
import { computed, ref } from "vue"
import { trans } from 'laravel-vue-i18n'

import { useTabChange } from "@/Composables/tab-change"
import { capitalize } from "@/Composables/capitalize"
import Button from '@/Components/Elements/Buttons/Button.vue';
import TableImages from "@/Pages/Tables/TableImages.vue"

library.add(faImagePolaroid,faCloudUpload)

const props = defineProps<{
    pageHead: object
    tabs: {
        current: string
        navigation: object
    }
    title: string
    uploaded_images?: object
    stock_images?: object
}>()

let currentTab = ref(props.tabs.current)
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab)

const component = computed(() => {
    const components = {
        uploaded_images: TableImages,
        stock_images: TableImages,
    }
    return components[currentTab.value]
})

const selectedRow = ref([])
</script>

<template layout="TenantApp">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead">
        <template #button>
            <Link
                href="d"
                :method="'post'"
                as="button"
            >
                <Button :key="selectedRow.length" size="xs"
                    :style="selectedRow.length > 0 ? 'primary' : 'tertiary'"
                    class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2"
                >
                    {{ selectedRow.length > 0 ? trans(`Create Banner (${selectedRow.length})`) : trans('Select images') }}
                </Button>
            </Link>
        </template>
    </PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" :selectedRow="selectedRow"/>
    <component :is="component" @selected-row="(value: any) => selectedRow = value" :tab="currentTab" :data="props[currentTab]" />
</template>


