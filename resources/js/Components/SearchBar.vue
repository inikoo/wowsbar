<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Mon, 06 Mar 2023 13:45:35 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { computed, ref } from 'vue'
import Image from '@/Components/Image.vue'
import {
    Combobox,
    ComboboxOptions,
    ComboboxOption,
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Link } from '@inertiajs/vue3'
import { Ref } from 'vue'
// import { router } from "@inertiajs/vue3"
import { useLayoutStore } from '@/Stores/layout'
import Button from '@/Components/Elements/Buttons/Button.vue';

import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@fad'
import { faSeedling } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
library.add(faSpinnerThird, faSeedling)

const open = ref(true)
const query = ref('')
const searchInput: Ref<string> = ref('')

let timeoutId: any

const handleSearchInput = () => {
    if(searchInput.value.length === 1) {
        fetchApi(searchInput.value)
    }
    else {
        clearTimeout(timeoutId)
        timeoutId = setTimeout(() => {
            fetchApi(searchInput.value)
        }, 400)
    }
}

const loadingState = ref(false)


const routeLink = (query: string) => route(`${useLayoutStore().systemName}.search.index`, {
    _query: {
        q: query,
    },
});


const resultsSearch = ref()
const paramsToString = computed(() => {
    return route().v().params ? '&' + Object.entries(route().v().params).map(([key, value]) => `param_${key}=${value}`).join('&') : ''
})

const fetchApi = async (query: string) => {
    if (query !== '') {
        resultsSearch.value = null
        loadingState.value = true
        await fetch(routeLink(query))
            .then(response => {
                response.json().then((data: Object) => {
                    resultsSearch.value = data
                    loadingState.value = false

                })
            })
            .catch(err => console.log(err))
    }
    else {
        // comboValue.value = 'Select Users'
    }
}



function handleKeyDown() {
    clearTimeout(timeoutId)
}

</script>

<template>
    <TransitionRoot :show="open" as="template" @after-leave="query = ''" appear>
        <Dialog as="div" class="relative z-[29]" @close="open = false">
            <!-- Background -->
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity" />
            </TransitionChild>
            
            <div class="fixed inset-0 z-10 overflow-y-auto pt-20 px-12">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-1000" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                    <DialogPanel class="mx-auto max-w-3xl transform divide-y divide-gray-100 overflow-hidden rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                        <Combobox v-slot="{ activeOption }">
                            <div class="relative">
                                <FontAwesomeIcon class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" aria-hidden="true" icon="fa-regular fa-search" size="lg"/>
                                <input type="text" v-model="searchInput" @input="handleSearchInput" @keydown="handleKeyDown"
                                class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-600 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search..." @change="query = $event.target.value">
                            </div>
                            <ComboboxOptions  class="flex divide-x divide-gray-100" as="div" static hold>

                                <!-- Left: Result Panel -->
                                <div :class="['h-fit min-w-0 flex-auto scroll-py-4 overflow-y-auto px-6 py-4 transition-all duration-500 ease-in-out', {'sm:h-96': false}]">
                                    <div hold class="-mx-2 text-sm text-gray-600">
                                        <!-- Looping: Results -->
                                        <ComboboxOption v-if="resultsSearch?.data.length > 0" v-for="(item, itemIndex) in resultsSearch?.data" :key="itemIndex" :value="item" as="div" v-slot="{ active }">
                                            <Link v-if="item.model?.route?.name" :href="`${route(item.model?.route?.name, item.model?.route?.parameters)}`"
                                                class="group flex relative cursor-pointer select-none items-center rounded p-2 gap-x-2" :class="[active ? 'bg-gray-100 text-gray-600' : '']">
                                                <FontAwesomeIcon :icon='item.model.icon' class='' aria-hidden='true' />

                                                <div class="w-full">
                                                    <div v-if="item.model_type == 'CustomerUser'">
                                                        <span class="truncate">{{ item.model.contact_name }}</span>
                                                    </div>
                                                    <div v-else class="truncate font-semibold">
                                                        {{ item.model.name ?? item.model.email ?? item.model.phone ?? 'Unknown' }}
                                                    </div>
                                                </div>

                                                <FontAwesomeIcon icon="fa-regular fa-chevron-right" v-if="active" class="relative h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                                            </Link>
                                            
                                            <div v-else>
                                                {{ item.model ?? '' }}
                                            </div>
                                        </ComboboxOption>

                                        <!-- Loading: fetching -->
                                        <div v-else-if="loadingState" class="">
                                            <div class="space-y-2">
                                                <div class="w-full rounded-md flex pl-0.5 gap-x-1 overflow-hidden">
                                                    <div class="w-8 h-9 skeleton rounded-l-md" />
                                                    <div class="w-full skeleton"/>
                                                </div>
                                                <div class="w-full rounded-md flex pl-0.5 gap-x-1 overflow-hidden">
                                                    <div class="w-8 h-9 skeleton rounded-l-md" />
                                                    <div class="w-full skeleton"/>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Initial state or no result -->
                                        <div v-else class="p-2">
                                            {{ trans('Nothing to show') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Detail Panel -->
                                <div class="hidden h-96 w-1/2 flex-none flex-col divide-y divide-gray-100 overflow-y-auto sm:flex">
                                    <!-- Loading: fetching -->
                                    <div v-if="loadingState">
                                        <div class="flex-none p-6 text-center">
                                            <div class="mx-auto h-16 w-16 rounded-full skeleton" />
                                            <div class="mt-3 skeleton w-1/2 mx-auto h-5" />
                                        </div>
                                        <div class="flex flex-auto flex-col justify-between gap-y-4 p-6">
                                            <div v-for=" of 3" class="flex gap-x-2 h-7 rounded overflow-hidden">
                                                <div class="skeleton w-20" />
                                                <div class="skeleton w-full" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hover the result -->
                                    <div v-else-if="activeOption">
                                        
                                        <div class="flex flex-auto flex-col justify-between p-6">
                                            <!-- CustomerUser -->
                                            <dl v-if="activeOption.model_type == 'CustomerUser'" class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm text-gray-600">
                                                <div class="flex-none p-6 text-center">
                                                    <div class="bg-gray-100 ring-2 ring-gray-300 shadow mx-auto h-16 w-16 rounded-full overflow-hidden">
                                                        <Image :src="activeOption.model.avatar" :alt="activeOption.model.code" />
                                                    </div>
                                                    <h2 class="mt-3 font-semibold text-gray-600">
                                                        {{ activeOption.model.contact_name }}
                                                    </h2>
                                                </div>

                                                <dt class="col-end-1 font-semibold text-gray-600">Email:</dt>
                                                <dd>{{ activeOption.model.email }}</dd>
                                                <dt class="col-end-1 font-semibold text-gray-600">Roles:</dt>
                                                <dd>{{ activeOption.model.roles }}</dd>
                                            </dl>

                                            <!-- Banner -->
                                            <dl v-if="activeOption.model_type == 'Banner'" class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm text-gray-600">
                                                <div class="flex-none p-6 text-center">
                                                    <div class="bg-gray-100 ring-2 shadow mx-auto h-16 w-16 rounded-full overflow-hidden"
                                                        :class="activeOption.model.state_icon.tooltip == 'unpublished' ? 'ring-indigo-300' : activeOption.model.state_icon.tooltip == 'live' ? 'ring-green-500' : 'ring-gray-500'">
                                                        <Image :src="activeOption.model.avatar" :alt="activeOption.model.code" />
                                                    </div>
                                                </div>

                                                <dt class="col-end-1 font-semibold text-gray-600">Name:</dt>
                                                <dd>{{ activeOption.model.name }}</dd>
                                                <dt class="col-end-1 font-semibold text-gray-600">Status:</dt>
                                                <dd class="capitalize space-x-1">
                                                    <span>{{ activeOption.model.state_icon.tooltip }}</span>
                                                    <FontAwesomeIcon :icon='activeOption.model.state_icon.icon' aria-hidden='true' :class="[activeOption.model.state_icon.class]" />
                                                </dd>
                                                <dt class="col-end-1 font-semibold text-gray-600">Website:</dt>
                                                <dd>{{ activeOption.model.website }}</dd>
                                            </dl>

                                            <!-- Website -->
                                            <dl v-if="activeOption.model_type == 'PortfolioWebsite'" class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm text-gray-600">
                                                <div class="flex-none p-6 text-center">
                                                    <div class="bg-gray-100 ring-2 ring-gray-300 shadow mx-auto h-16 w-16 rounded-full overflow-hidden">
                                                        <Image :src="activeOption.model.avatar" :alt="activeOption.model.code" />
                                                    </div>
                                                </div>
                                                <dt class="col-end-1 font-semibold text-gray-600">Name:</dt>
                                                <dd>{{ activeOption.model.name }}</dd>
                                                <dt class="col-end-1 font-semibold text-gray-600">Banners count:</dt>
                                                <dd>{{ activeOption.model.banner }}</dd>
                                            </dl>

                                            <!-- Prospect -->
                                            <dl v-if="activeOption.model_type == 'Prospect'" class="flex flex-col gap-x-6 gap-y-3 text-sm text-gray-500">
                                                <div class="p-3 text-center text-lg font-bold capitalize">
                                                    <span :class="{'opacity-50 font-normal italic mr-1': !activeOption.model.name}">{{ activeOption.model.name ?? trans('unknown')}}</span>
                                                    <FontAwesomeIcon :icon='activeOption.model.state_icon.icon' :class='activeOption.model.state_icon.class' :title="activeOption.model.state_icon.tooltip" aria-hidden='true' />
                                                </div>
                                                
                                                <div class="">
                                                    <dt v-if="activeOption.model.email" class="font-semibold">Email: <span class="font-normal ml-1">{{ activeOption.model.email }}</span></dt>
                                                    <dt v-if="activeOption.model.phone" class="font-semibold">Phone: <span class="font-normal ml-1">{{ activeOption.model.phone }}</span></dt>
                                                    <dt v-if="activeOption.model.website" class="font-semibold">Website: <span class="font-normal ml-1">{{ activeOption.model.website }}</span></dt>
                                                    <dt v-if="activeOption.model.tags.length > 0" class="flex gap-x-1.5">
                                                        <div class="font-semibold text-gray-600">Tags:</div>
                                                        <div class="flex gap-x-1 gap-y-1.5">
                                                            <Tag v-for="tag in activeOption.model.tags" stringToColor :label="tag" />
                                                        </div>
                                                    </dt>
                                                </div>

                                                <Link :href="route(activeOption.model.route.name, activeOption.model.route.parameters)" class="mt-4">
                                                    <Button full label="open" />
                                                </Link>
                                            </dl>


                                            <!-- <button type="button" class="mt-6 w-full rounded-md bg-gray-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Send message</button> -->
                                        </div>
                                    </div>
                                <!-- <pre>{{ activeOption }}</pre> -->
                                </div>
                            </ComboboxOptions>
                        </Combobox>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
