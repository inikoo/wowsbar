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
import { computed, ref, Ref } from "vue"
import { trans } from 'laravel-vue-i18n'

import { useTabChange } from "@/Composables/tab-change"
import { capitalize } from "@/Composables/capitalize"
import Button from '@/Components/Elements/Buttons/Button.vue';
import TableImages from "@/Pages/Tables/TableImages.vue"
import { watch } from 'vue'

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
const handleTabUpdate = (tabSlug: string) => useTabChange(tabSlug, currentTab)

const component = computed(() => {
    const components = {
        uploaded_images: TableImages,
        stock_images: TableImages,
    }
    return components[currentTab.value]
})

const selectedRow: Ref<any> = ref({})
const isSelectImage = ref(false)

const combinedImages: Ref<any> = computed(() => {
    return Object.values(selectedRow.value).reduce((accumulator: any, currentValue) => {
    if (Array.isArray(currentValue)) {
        return accumulator.concat(currentValue)
    } else {
        return accumulator
    }
}, [])
})

</script>

<template layout="TenantApp">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead">
        <template #button>
            <!-- Button: Initial state -->
            <Button
                v-if="!isSelectImage"
                @click="isSelectImage = true"
                size="xs"
                :style="`tertiary`"
            >
                Select images
            </Button>

            <!-- Button: Create Banner -->
            <div v-if="isSelectImage" class="flex gap-x-2">
                <Button :style="'delete'" @click="isSelectImage = false" size="xs">
                    Cancel select
                </Button>
                <Link
                    href="d"
                    :method="'post'"
                >
                    <Button :key="combinedImages.length" size="xs"
                        :style="combinedImages.length > 0 ? 'primary' : 'tertiary'"
                        :class="[combinedImages.length > 0 ? '' : 'cursor-not-allowed']"
                    >
                        Create Banner ({{ combinedImages.length }})
                    </Button>
                    
                    <!-- <Button v-else :style="`tertiary`" :key="selectedRow?.[currentTab]?.length">
                        None is selected
                    </Button> -->
                </Link>
            </div>
        </template>
    </PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" :selectedRow="selectedRow" :isSelectImage="isSelectImage"/>
    <KeepAlive>
        <component :isSelectImage="isSelectImage" :is="component" :key="currentTab" @selected-row="(value: any) => selectedRow[currentTab] = value[currentTab]" :tab="currentTab" :data="props[currentTab]"></component>
    </KeepAlive>
</template>


