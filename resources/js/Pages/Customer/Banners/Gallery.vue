div<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:26:44 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import Tabs from "@/Components/Navigation/Tabs.vue"
import Input from '@/Components/Forms/Fields/Input.vue'
import { computed, ref, Ref } from "vue"
import { trans } from 'laravel-vue-i18n'
import Select from '@/Components/Forms/Fields/Primitive/PrimitiveSelect.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faImagePolaroid, faCloudUpload, faTimes } from '@/../private/pro-light-svg-icons'
import { faArrowRight } from '@/../private/pro-regular-svg-icons'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

import { useTabChange } from "@/Composables/tab-change"
import { capitalize } from "@/Composables/capitalize"
import Button from '@/Components/Elements/Buttons/Button.vue'
import TableImages from "@/Components/Tables/TableImages.vue"
import { get } from 'lodash'
import Image from '@/Components/Image.vue'


import { watch } from 'vue'
import Modal from '@/Components/Utils/Modal.vue'
import axios from 'axios'

library.add(faImagePolaroid, faCloudUpload, faSpinnerThird, faTimes, faArrowRight)

const props: any = defineProps<{
    pageHead: any
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

// Component: Tabs
const component = computed(() => {
    const components: any = {
        uploaded_images: TableImages,
        stock_images: TableImages,
    }
    return components[currentTab.value]
})

const selectedImages: Ref<any> = ref({})
const isSelectImage = ref(false)
const loadingState = ref(false)
const websitesList = ref([])
const isModalOpen = ref(false)
const fieldWebsite = ref()
const fieldName = ref()
const fieldCode = ref()

const combinedImages: Ref<any> = computed(() => {
    return Object.values(selectedImages.value).reduce((accumulator: any, currentValue) => {
        if (Array.isArray(currentValue)) {
            return accumulator.concat(currentValue)
        } else {
            return accumulator
        }
    }, [])
})

const compWebsitesList = computed(() => {
    return websitesList.value.map(obj => { return obj.slug })
})

const selectedImagesFlat = computed(() => {
    // To return the double array to flat
    return [].concat(...(Object.values(selectedImages.value)))
})

const createBanner = async () => {
    loadingState.value = true
    try {
        if (fieldWebsite.value) {
            await axios.post(
                route('models.portfolio-website.banner.gallery.store', fieldWebsite.value),
                {
                    images: selectedImagesFlat.value,
                    name: fieldName.value
                },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            )

            loadingState.value = false
            setTimeout(() => {
                isModalOpen.value = false
            }, 1000)
        }

        if (!fieldWebsite.value) {
            await axios.post(
                route('customer.models.banner.store.from-gallery'),
                {
                    images: selectedImagesFlat.value,
                    name: fieldName.value
                },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            )

            loadingState.value = false
            setTimeout(() => {
                isModalOpen.value = false
            }, 1000)
        }
    } catch (error: any) {
        // console.error("===========================")
        console.error(error.message)
        loadingState.value = false
    }
}

const selectedImage = () => {
    const allImage = [...get(props, ["uploaded_images", 'data'], []), ...get(props, ['stock_images', 'data'], [])];
    return allImage.filter((item) => get(selectedImages, ['value', 'stock_images'], []).includes(item.id));
};

const deleteImageSelected = (image) => {
    const index = selectedImages.value.stock_images.findIndex((item) => item == image);
    if (index !== -1) {
        selectedImages.value.stock_images.splice(index, 1);
        allImageFlat.value = selectedImage();
    }
};


// Fetch website list
watch(isModalOpen, async () => {
    try {
        const response = await axios.get(route('customer.portfolio.websites.index'))
        websitesList.value = response.data.data
        allImageFlat.value = selectedImage();
        // fieldWebsite.value = websitesList.value[0].slug
        loadingState.value = false
    } catch (error) {
        console.log(error)
        loadingState.value = false
    }
})

const allImageFlat = ref(selectedImage());

// console.log('debug:', props.uploaded_images, props.stock_images)

</script>

<template layout="CustomerApp">
    <!--suppress HtmlRequiredTitleElement -->

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button>
            <!-- Button: Initial state -->
            <Button v-if="!isSelectImage" @click="isSelectImage = true" size="xs" :style="`tertiary`" id="select-images">
                {{ trans('Choose images for a new banner') }}
            </Button>

            <!-- Button: Create Banner -->
            <div v-if="isSelectImage" class="flex gap-x-2">
                <Button :style="'delete'" @click="isSelectImage = false"  size="xs" id="cancel-select">
                    <FontAwesomeIcon icon='fal fa-times' class='' aria-hidden='true' />
                    {{ trans('Cancel') }}
                </Button>
                <Button :key="combinedImages.length" size="xs" :style="combinedImages.length > 0 ? 'primary' : 'disabled'"
                    :class="[combinedImages.length > 0 ? '' : 'cursor-not-allowed']"
                    @click="combinedImages.length > 0 ? isModalOpen = true : false" id="create-banner">
                    {{ trans('Next') }} ({{ combinedImages.length }})
                    <FontAwesomeIcon v-if="combinedImages.length" icon='far fa-arrow-right' class='' aria-hidden='true' />
                </Button>
            </div>
        </template>
    </PageHeading>

    <!-- Tabs -->
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" :selectedRow="selectedImages"
        :isSelectImage="isSelectImage">
        <template #addTitle="{ tabSlug }">
            {{
                isSelectImage
                ? selectedImages[tabSlug]?.length
                    ? trans(`(${selectedImages[tabSlug]?.length})`)
                    : trans(`(0)`)
                : ''
            }}
        </template>
    </Tabs>

    <!-- Content: Table from the Tab -->
    <KeepAlive>
        <component :isSelectImage="isSelectImage" :is="component" :key="currentTab"
            @selected-row="(value: any) => selectedImages[currentTab] = value[currentTab]" :tab="currentTab"
            :data="props[currentTab]" />
    </KeepAlive>

    <!-- Popup: select Website to create Banner -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false" width="w-2/5">
        <div class="flex flex-col gap-y-4">
            <!-- Field -->
            <div class="flex flex-col gap-y-4">
                <div class="max-w-full">
                    <!-- Field: Website -->
                    <div>{{ trans('Select website') }}</div>
                    <Select :value="fieldWebsite" :fieldData="{ options: compWebsitesList }"
                        @onChange="(newValue) => fieldWebsite = newValue" />
                </div>


                <!-- Field: Name -->
                <div class="max-w-full">
                    <div>{{ trans('Name') }}</div>
                    <input v-model.trim="fieldName" placeholder="Enter name for new banner"
                        class="block w-full shadow-sm rounded-md text-gray-600 dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500" />
                </div>
            </div>

            <div class="max-w-full">
                Images Banner
                <div class="flex flex-wrap gap-x-2 gap-y-2">
                    <div v-for="image in allImageFlat" :key="image.id" class="relative">
                        <Image :src="image.thumbnail" class="flex items-center justify-center h-7 shadow " />
                        <button
                            class="absolute top-0 text-xs right-0 translate-x-1/2 -translate-y-1/2 flex items-center justify-center px-1 bg-gray-200 hover:bg-gray-300 p-1 text-red-500 rounded-full h-2.5 w-2.5"
                            @click="() => deleteImageSelected(image.id)">
                            <FontAwesomeIcon :icon="['fal', 'times']" class="text-[7px] leading-none"/>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Button: Create -->
            <Button @click="createBanner" class="flex justify-center">
                <div class="px-3 relative flex items-center justify-center">
                    <span :class="{ 'opacity-0': loadingState }">{{ trans('Create') }}</span>
                    <FontAwesomeIcon icon='fad fa-spinner-third' class="w-5 h-5 absolute ml-1 animate-spin"
                        :class="{ 'opacity-0': !loadingState }" aria-hidden='true' />
                </div>
            </Button>
        </div>
    </Modal>
</template>


