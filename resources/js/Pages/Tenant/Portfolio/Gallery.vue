<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 14:45:02 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import Tabs from "@/Components/Navigation/Tabs.vue"
import { computed, ref, Ref } from "vue"
import { trans } from 'laravel-vue-i18n'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faImagePolaroid, faCloudUpload } from '@/../private/pro-light-svg-icons'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

import { useTabChange } from "@/Composables/tab-change"
import { capitalize } from "@/Composables/capitalize"
import Button from '@/Components/Elements/Buttons/Button.vue'
import TableImages from "@/Pages/Tables/TableImages.vue"
import Select from '@/Components/Forms/Fields/Select.vue'

import { watch } from 'vue'
import Modal from '@/Components/Utils/Modal.vue'
import axios from 'axios'

library.add(faImagePolaroid, faCloudUpload, faSpinnerThird)

const props: any = defineProps<{
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
    const components: any = {
        uploaded_images: TableImages,
        stock_images: TableImages,
    }
    return components[currentTab.value]
})

const selectedImages: Ref<any> = ref({})
const isSelectImage = ref(false)

const combinedImages: Ref<any> = computed(() => {
    return Object.values(selectedImages.value).reduce((accumulator: any, currentValue) => {
    if (Array.isArray(currentValue)) {
        return accumulator.concat(currentValue)
    } else {
        return accumulator
    }
}, [])
})

const isModalOpen = ref(false)
const selectedWebsite = ref([])
const compSelectedWebsite = computed(() => {
    return selectedWebsite.value.map(obj => {return obj.slug})
})
const loadingState = ref(false)

const compselectedImagesFlat = computed(() => {
    // To return the double array is flat
    return [].concat(...(Object.values(selectedImages.value)))
})

const createBanner = async () => {
    loadingState.value = true
    try {
        const response = await axios.post(
            route('models.portfolio-website.banner.gallery.store', selectedWebsite.value),
            { images:  compselectedImagesFlat.value},
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )
        loadingState.value = false
        setTimeout(() => {
            isModalOpen.value = false
        }, 1000);
    } catch (error) {
        console.error("===========================")
        console.log(error)
        loadingState.value = false
    }
}

watch(isModalOpen, async () => {
    try {
        const response = await axios.get(route('portfolio.websites.index'))
        selectedWebsite.value = response.data.data
        loadingState.value = false
    } catch (error) {
        console.log(error)
        loadingState.value = false
    }
})

const form = useForm({'Gallery': null})

</script>

<template layout="TenantApp">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead">
        <template #button>
            <!-- Button: Initial state -->
            <Button v-if="!isSelectImage" @click="isSelectImage = true" size="xs" :style="`tertiary`" id="select-images">
                Select images
            </Button>

            <!-- Button: Create Banner -->
            <div v-if="isSelectImage" class="flex gap-x-2">
                <Button :style="'delete'" @click="isSelectImage = false" size="xs"  id="cancel-select">
                    Cancel select
                </Button>
                <Button :key="combinedImages.length" size="xs"
                    :style="combinedImages.length > 0 ? 'primary' : 'disabled'"
                    :class="[combinedImages.length > 0 ? '' : 'cursor-not-allowed']"
                    @click="combinedImages.length > 0 ? isModalOpen = true : false"
                    id="create-banner"
                >
                    Create Banner ({{ combinedImages.length }})
                </Button>
            </div>
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" :selectedRow="selectedImages" :isSelectImage="isSelectImage"/>
    
    <!-- Content: Table from the Tab -->
    <KeepAlive>
        <component :isSelectImage="isSelectImage" :is="component" :key="currentTab" @selected-row="(value: any) => selectedImages[currentTab] = value[currentTab]" :tab="currentTab" :data="props[currentTab]" />
    </KeepAlive>

    <!-- Popup: select Website to create Banner -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <Select :options="compSelectedWebsite" fieldName="Gallery" :form="form" class="mb-44" />
        <Button @click="createBanner">
            <div class="px-3 relative flex items-center justify-center">
                <span :class="{'opacity-0': loadingState}">{{ trans('Create') }}</span>
                <FontAwesomeIcon icon='fad fa-spinner-third' class="w-5 h-5 absolute ml-1 animate-spin" :class="{'opacity-0': !loadingState}" aria-hidden='true' />
            </div>
        </Button>
    </Modal>
</template>


