<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 14:45:02 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import Tabs from "@/Components/Navigation/Tabs.vue"
import Input from '@/Components/Forms/Fields/Input.vue'
import { computed, ref, Ref } from "vue"
import { trans } from 'laravel-vue-i18n'

import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

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
const loadingState = ref(false)
const websitesList = ref([])
const isModalOpen = ref(false)
const fieldWebsite = ref()
const fieldName = ref()

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

const compselectedImagesFlat = computed(() => {
    // To return the double array to flat
    return [].concat(...(Object.values(selectedImages.value)))
})

const createBanner = async () => {
    loadingState.value = true
    try {
        if(fieldWebsite.value){
            await axios.post(
                route('models.portfolio-website.banner.gallery.store', fieldWebsite.value),
                {
                    images: compselectedImagesFlat.value,
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
        
        if(!fieldWebsite.value){
            await axios.post(
                route('models.tenant.banner.gallery.store'),
                {
                    images: compselectedImagesFlat.value,
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

// Fetch website list
watch(isModalOpen, async () => {
    try {
        const response = await axios.get(route('portfolio.websites.index'))
        websitesList.value = response.data.data
        // fieldWebsite.value = websitesList.value[0].slug
        loadingState.value = false
    } catch (error) {
        console.log(error)
        loadingState.value = false
    }
})

</script>

<template layout="TenantApp">
    <!--suppress HtmlRequiredTitleElement -->

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button>
            <!-- Button: Initial state -->
            <Button v-if="!isSelectImage" @click="isSelectImage = true" size="xs" :style="`tertiary`" id="select-images">
                Select images
            </Button>

            <!-- Button: Create Banner -->
            <div v-if="isSelectImage" class="flex gap-x-2">
                <Button :style="'delete'" @click="isSelectImage = false" size="xs" id="cancel-select">
                    Cancel select
                </Button>
                <Button :key="combinedImages.length" size="xs" :style="combinedImages.length > 0 ? 'primary' : 'disabled'"
                    :class="[combinedImages.length > 0 ? '' : 'cursor-not-allowed']"
                    @click="combinedImages.length > 0 ? isModalOpen = true : false"
                    id="create-banner"
                    >
                    Create Banner ({{ combinedImages.length }})
                </Button>
            </div>
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" :selectedRow="selectedImages"
        :isSelectImage="isSelectImage" />

    <!-- Content: Table from the Tab -->
    <KeepAlive>
        <component :isSelectImage="isSelectImage" :is="component" :key="currentTab"
            @selected-row="(value: any) => selectedImages[currentTab] = value[currentTab]" :tab="currentTab"
            :data="props[currentTab]" />
    </KeepAlive>

    <!-- Popup: select Website to create Banner -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="flex flex-col gap-y-4">
            <!-- Field -->
            <div class="flex flex-col gap-y-4">
                <div class="max-w-md">
                    <!-- Field: Website -->
                    <div>Select website</div>
                    <Listbox v-model="fieldWebsite">
                        <div class="relative mt-1">
                            <ListboxButton
                                class="relative w-full rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm">
                                <span class="block truncate">{{ fieldWebsite ?? 'Select website'}}</span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                </span>
                            </ListboxButton>

                            <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100"
                                leave-to-class="opacity-0">
                                <ListboxOptions
                                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                    <ListboxOption v-slot="{ active, selected }" v-for="person in compWebsitesList" :key="person.name"
                                        :value="person" as="template">
                                        <li :class="[
                                            selected ? 'bg-gray-500 text-white' : 'text-gray-700',
                                            active ? 'bg-gray-300 text-gray-100 cursor-pointer' : 'text-gray-700',
                                            'relative select-none py-2 pl-10 pr-4',
                                        ]">
                                            <span :class="[
                                                selected ? 'font-medium' : 'font-normal',
                                                'block truncate',
                                            ]">{{ person }}</span>
                                            <span v-if="selected"
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-100">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                </div>

                <!-- Field: Name -->
                <div class="max-w-md">
                    <div>Name</div>
                    <input v-model.trim="fieldName" placeholder="Enter name for new banner"
                        class="block w-full shadow-sm rounded-md text-gray-600 dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500" />
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


