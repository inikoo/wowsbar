<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Mon, 06 Mar 2023 13:45:35 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { computed, ref } from 'vue'
import {
    Combobox,
    ComboboxOptions,
    ComboboxOption,
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Ref } from 'vue'
import { router } from "@inertiajs/vue3"
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faSpinnerThird)

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

const resultsSearch = ref()
const paramsToString = computed(() => {
    return route().v().params ? '&' + Object.entries(route().v().params).map(([key, value]) => `param_${key}=${value}`).join('&') : ''
})

const fetchApi = async (query: string) => {
    if (query !== '') {
        resultsSearch.value = null
        loadingState.value = true
        await fetch(`http://aiku.wowsbar.test/search/?q=${query}&route_src=${route().current()}${paramsToString.value}`)
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
                        <Combobox v-slot="{ activeOption }" @update:modelValue="">
                            <div class="relative">
                                <FontAwesomeIcon class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" aria-hidden="true" icon="fa-regular fa-search" size="lg"/>
                                <input type="text" v-model="searchInput" @input="handleSearchInput" @keydown="handleKeyDown"
                                class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-700 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search..." @change="query = $event.target.value">
                            </div>
                            <ComboboxOptions  class="flex divide-x divide-gray-100" as="div" static hold>

                                <!-- Left: Result Panel -->
                                <div :class="['h-fit min-w-0 flex-auto scroll-py-4 overflow-y-auto px-6 py-4 transition-all duration-500 ease-in-out', {'sm:h-96': false}]">
                                    <div hold class="-mx-2 text-sm text-gray-700">
                                        <!-- Looping: Results -->
                                        <ComboboxOption v-if="resultsSearch?.data.length > 0" v-for="item in resultsSearch?.data" :key="item.id" :value="item" as="template" v-slot="{ active }">
                                            <div>
                                                <Link :href="`${route(item.model.route.name, item.model.route.parameters)}`" :class="['group flex cursor-pointer select-none items-center rounded-md p-2', active && 'bg-gray-100 text-gray-700']">
                                                    <!-- <img :src="item.imageUrl" alt="" class="h-6 w-6 flex-none rounded-full" /> -->
                                                    <FontAwesomeIcon :icon='item.model.icon' class='' aria-hidden='true' />
                                                    <span class="ml-3 flex-auto truncate">{{ item.model.name }}</span>
                                                    <FontAwesomeIcon icon="fa-regular fa-chevron-right" v-if="active" class="ml-3 h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                                                </Link>
                                            </div>
                                        </ComboboxOption>

                                        <!-- Loading: fetching -->
                                        <div v-else-if="loadingState" class="">
                                            <!-- Skeleton Loader -->
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
                                        <div v-else class="">
                                            {{ trans('Nothing to show') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Detail Panel -->
                                <div class="hidden h-96 w-1/2 flex-none flex-col divide-y divide-gray-100 overflow-y-auto sm:flex">
                                    <!-- Loading: fetching -->
                                    <div v-if="loadingState">
                                        <!-- Loading: Avatar -->
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
                                        <div class="flex-none p-6 text-center">
                                            <img :src="activeOption.imageUrl" :alt="activeOption.model.code" class="bg-gray-400 mx-auto h-16 w-16 rounded-full" />
                                            <h2 class="mt-3 font-semibold text-gray-700">
                                                {{ activeOption.model_type }}
                                            </h2>
                                            <p class="text-sm leading-6 text-gray-500">{{ activeOption.role }}</p>
                                        </div>
                                        <div class="flex flex-auto flex-col justify-between p-6">
                                            <dl class="grid grid-cols-1 gap-x-6 gap-y-3 text-sm text-gray-700">
                                                <dt class="col-end-1 font-semibold text-gray-700">Domain</dt>
                                                <dd>{{ activeOption.model.domain }}</dd>
                                                <dt class="col-end-1 font-semibold text-gray-700">Code</dt>
                                                <dd class="truncate">
                                                    {{ activeOption.model.code }}
                                                </dd>
                                                <!-- <dt class="col-end-1 font-semibold text-gray-700">Icon</dt>
                                                <dd class="truncate">
                                                    {{ activeOption.model.icon }}
                                                </dd> -->
                                            </dl>
                                            <!-- <button type="button" class="mt-6 w-full rounded-md bg-gray-700 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Send message</button> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- <pre>{{ activeOption }}</pre> -->
                            </ComboboxOptions>
                        </Combobox>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
