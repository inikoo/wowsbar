<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 20 Sep 2023 09:17:50 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import {trans} from 'laravel-vue-i18n'

import {computed, ref} from "vue"
import {Link, router} from "@inertiajs/vue3"
import {Menu, MenuButton, MenuItems, MenuItem} from "@headlessui/vue"
import {library} from "@fortawesome/fontawesome-svg-core"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {faChevronRight} from '@far'
import {faSparkles, faArrowFromLeft, faArrowLeft, faArrowRight} from '@fas'
import {faChartNetwork, faInboxOut} from '@fal'

import {capitalize} from "@/Composables/capitalize"
import {useLayoutStore} from "@/Stores/layout"
import { routeType } from '@/types/route'

library.add(faSparkles, faArrowFromLeft, faArrowLeft, faArrowRight, faChevronRight, faInboxOut, faChartNetwork);

const props = defineProps<{
    breadcrumbs: {
        type: string
        simple: {
            icon?: string
            overlay?: string
            label?: string
            route?: routeType
        }
        creatingModel: {
            label?: string
        }
        modelWithIndex: {
            index: {
                icon?: string
                label?: string
                route?: routeType
            }
            model: {
                icon?: string
                label?: string
                route?: routeType
            }
        }
        suffix?: string
        options?: object
    }[]
    navigation: {
        next?: {
            label?: string,
            route?: routeType
        }
        previous?: {
            label?: string,
            route?: routeType
        }
    }
}>();

const compDisplayBreadcrumbs = computed(() => {
    return Object.keys(props["breadcrumbs"]).length > 0;
})

// Get parameter for Prev & Next button to stay on same tab
const urlParameter = ref('showcase')
router.on('navigate', (event) => {
    const params = new URLSearchParams(location.search.substring(1))
    const filteredParams = {}
    const patternToDelete = /\[global\]$/  // to filter su_filter[global], etc
    for (const [key, value] of params.entries()) {
        if (!patternToDelete.test(key)) {
            filteredParams[key] = value
        }
    }
    urlParameter.value = `?${new URLSearchParams(filteredParams).toString()}`
})

const layoutState = useLayoutStore()

</script>

<template>
    <div v-if="compDisplayBreadcrumbs" class="transition-all duration-200 ease-in-out border-b border-gray-200"
        :class="[layoutState.leftSidebar.show ? 'pr-0 md:pr-56' : 'pr-0 md:pr-10']"
    >
        <nav
            class="py-4 md:py-0 flex h-6 text-sm bg-gray-50 text-gray-700"
            aria-label="Breadcrumb">
            <!-- Breadcrumb -->
            <ol role="list" class="w-full mx-auto px-4 flex">
                <li
                    v-for="(breadcrumb, breadcrumbIdx) in breadcrumbs"
                    :key="breadcrumbIdx"
                    class="hidden first:flex last:flex md:flex">
                    <div class="flex items-center">
                        <!-- Shorter Breadcrumb on Mobile size -->
                        <div v-if="breadcrumbs.length > 2 && breadcrumbIdx != 0" class="md:hidden">
                            <FontAwesomeIcon
                                v-if="breadcrumbIdx !== 0"
                                class="flex-shrink-0 h-3 w-3 mx-3 opacity-50"
                                icon="fa-regular fa-chevron-right"
                                aria-hidden="true"/>
                            <span>...</span>
                        </div>

                        <!-- The nodes -->
                        <template v-if="breadcrumb.type === 'simple'">
                            <FontAwesomeIcon
                                v-if="breadcrumbIdx !== 0"
                                class="flex-shrink-0 h-3 w-3 mx-3 opacity-50"
                                icon="fa-regular fa-chevron-right"
                                aria-hidden="true"/>
                            <component
                                :is="breadcrumb.simple.route ? Link : 'span'"
                                :class="'hover:text-gray-700' || ''"
                                :href="
									breadcrumb.simple.route
										? route(
												breadcrumb.simple.route.name,
												breadcrumb.simple.route.parameters
										  )
										: ''
								">
                                <FontAwesomeIcon
                                    v-if="breadcrumb.simple.icon"
                                    :class="breadcrumb.simple.label ?
                                     breadcrumb.simple.icon==='fal fa-bars' ? 'mr-1' : 'mr-2'
                                     :  ''"
                                    class="flex-shrink-0 h-3.5 w-3.5"
                                    :icon="breadcrumb.simple.icon"
                                    aria-hidden="true"/>
                                <span class="capitalize">{{ breadcrumb.simple.label }}</span>
                            </component>
                        </template>
                        <template v-else-if="breadcrumb.type === 'creatingModel'">
                            <FontAwesomeIcon
                                class="flex-shrink-0 h-3.5 w-3.5 mr-1 text-yellow-500 ml-2"
                                icon="fas fa-sparkles"
                                aria-hidden="true"/>
                            <span class="capitalize text-yellow-600 opacity-75">
								{{ breadcrumb.creatingModel.label }}
                            </span>
                        </template>
                        <template v-else-if="breadcrumb.type === 'modelWithIndex'">
                            <div class="hidden md:inline-flex">
                                <FontAwesomeIcon
                                    v-if="breadcrumbIdx !== 0"
                                    class="flex-shrink-0 h-3 w-3 mx-3 opacity-50 place-self-center"
                                    icon="fa-regular fa-chevron-right"
                                    aria-hidden="true"/>
                                <Link
                                    class="hover:text-gray-700 grid grid-flow-col items-center"
                                    :href="
										route(
											breadcrumb.modelWithIndex.index.route.name,
											breadcrumb.modelWithIndex.index.route.parameters
										)
									">
                                    <FontAwesomeIcon
                                        :icon="['fal', 'bars']"
                                        class="flex-shrink-0 h-3.5 w-3.5 mr-1"
                                        aria-hidden="true"/>
                                    <span class="capitalize">
                                        {{ breadcrumb.modelWithIndex.index.label }}
                                    </span>
                                </Link>
                            </div>
                            <span class="mx-3 select-none">→</span>
                            <Link
                                class="hover:text-gray-700"
                                :href="
									route(
										breadcrumb.modelWithIndex.model.route.name,
										breadcrumb.modelWithIndex.model.route.parameters
									)
								">
								<span class="capitalize">
									{{ breadcrumb.modelWithIndex.model.label }}
								</span>
                            </Link>
                        </template>
                        <span
                            :class="breadcrumb.type ? 'ml-1' : ''"
                            v-show="breadcrumb.suffix"
                            class="italic"
                        >
                            {{ breadcrumb.suffix }}
                        </span>
                    </div>
                </li>
            </ol>

            <!-- Popup for Breadcrumb List on Mobile -->
            <Menu as="div" class="z-10 w-full h-full absolute top-0 md:hidden">
                <MenuButton class="absolute w-full h-full"></MenuButton>
                <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="origin-top-right absolute left-4 top-9 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none">
                        <MenuItem
                            v-for="(breadcrumb, breadcrumbIdx) in breadcrumbs"
                            :key="breadcrumbIdx"
                            class="">
                            <template v-if="breadcrumb.type === 'simple'">
                                <component
                                    :is="breadcrumb.simple.route ? Link : 'span'"
                                    :class="
										'py-2 grid grid-flow-col items-center justify-start' || ''
									"
                                    :href="
										breadcrumb.simple.route
											? route(
													breadcrumb.simple.route.name,
													breadcrumb.simple.route.parameters
											  )
											: ''
									"
                                    :style="{ paddingLeft: 12 + breadcrumbIdx * 7 + 'px' }">
                                    <!-- Icon Section -->
                                    <FontAwesomeIcon
                                        v-if="breadcrumb.simple.icon && breadcrumbIdx == 0"
                                        class="flex-shrink-0 h-3.5 w-3.5"
                                        :icon="breadcrumb.simple.icon"
                                        aria-hidden="true"/>

                                    <!-- Icon Arrow -->
                                    <FontAwesomeIcon
                                        v-if="breadcrumbIdx != 0"
                                        class="flex-shrink-0 h-3.5 w-3.5 text-gray-300"
                                        icon="fa fa-arrow-from-left"
                                        aria-hidden="true"/>
                                    <span
                                        v-if="breadcrumbIdx == 0 && !breadcrumb.simple.label"
                                        class="grid grid-flow-cols justify-center font-bold capitalize ml-2"
                                    >{{ trans('Home') }}
									</span>
                                    <span
                                        class="capitalize grid grid-flow-col items-center ml-4 mr-3">
										{{ breadcrumb.simple.label }}
									</span>

                                    <!-- Icon List (Simple) -->
                                    <FontAwesomeIcon
                                        v-if="breadcrumb.simple.icon && breadcrumbIdx != 0"
                                        class="flex-shrink-0 h-3.5 w-3.5"
                                        :icon="breadcrumb.simple.icon"
                                        aria-hidden="true"/>
                                </component>
                            </template>
                            <template v-else-if="breadcrumb.type === 'creatingModel'">
								<span class="capitalize text-yellow-600 opacity-75">
									{{ breadcrumb.creatingModel.label }}
								</span>
                            </template>
                            <template v-else-if="breadcrumb.type === 'modelWithIndex'">
                                <div class="divide-y divide-gray-200">
                                    <Link
                                        class="py-2 grid grid-flow-col justify-start items-center"
                                        :href="
											route(
												breadcrumb.modelWithIndex.index.route.name,
												breadcrumb.modelWithIndex.index.route.parameters
											)
										"
                                        :style="{ paddingLeft: 12 + breadcrumbIdx * 7 + 'px' }">
                                        <FontAwesomeIcon
                                            class="flex-shrink-0 h-3.5 w-3.5 text-gray-300"
                                            icon="fa fa-arrow-from-left"
                                            aria-hidden="true"/>
                                        <span class="capitalize md:text-xs ml-4 mr-3">
											{{ breadcrumb.modelWithIndex.index.label }}
										</span>

                                        <!-- Icon List -->
                                        <FontAwesomeIcon
                                            :icon="['fal', 'bars']"
                                            class="flex-shrink-0 h-3.5 w-3.5"
                                            aria-hidden="true"/>
                                    </Link>

                                    <!-- Subpage -->
                                    <Link
                                        class="py-2 grid grid-flow-col justify-start items-center text-orange-400"
                                        :href="
											route(
												breadcrumb.modelWithIndex.model.route.name,
												breadcrumb.modelWithIndex.model.route.parameters
											)
										"
                                        :style="{
											paddingLeft: 12 + (breadcrumbIdx + 1) * 7 + 'px',
										}">
                                        <FontAwesomeIcon
                                            class="flex-shrink-0 h-3.5 w-3.5 mr-1 text-gray-300"
                                            icon="fa fa-arrow-from-left"
                                            aria-hidden="true"/>
                                        <span class="capitalize ml-4 mr-3">
											{{ breadcrumb.modelWithIndex.model.label }}
										</span>
                                    </Link>
                                </div>
                            </template>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
            <div v-if="props.navigation.previous || props.navigation.next" class="grid grid-flow-col justify-end items-center pr-2 space-x-2 text-sm text-gray-700 font-semibold">
                <!-- Button: Previous -->
                <div class="flex justify-center items-center w-8">
                    <Link v-if="props.navigation.previous" :href="route(props.navigation.previous?.route.name, props.navigation.previous?.route.parameters)  + urlParameter"
                          class="rounded w-full h-full flex items-center justify-center opacity-60 hover:opacity-100 hover:cursor-pointer hover:text-gray-800"
                          :title="capitalize(props.navigation.previous?.label)"
                    >
                        <FontAwesomeIcon icon="fas fa-arrow-left" class="" aria-hidden="true"/>
                    </Link>
                    <FontAwesomeIcon v-else icon="fas fa-arrow-left" class="opacity-20 cursor-pointer" aria-hidden="true"/>
                </div>

                <!-- Button: Next -->
                <div class="flex justify-center items-center w-8">
                    <Link v-if="props.navigation.next" class="rounded w-full h-full flex items-center justify-center opacity-60 hover:opacity-100 hover:cursor-pointer hover:text-gray-800"
                          :title="capitalize(props.navigation.next?.label)"
                          :href="route(props.navigation.next?.route.name, props.navigation.next?.route.parameters) + urlParameter"
                    >
                        <FontAwesomeIcon icon="fas fa-arrow-right" class="" aria-hidden="true"/>
                    </Link>
                    <FontAwesomeIcon v-else icon="fas fa-arrow-right" class="opacity-20 cursor-pointer" aria-hidden="true"/>
                </div>
            </div>
        </nav>


    </div>
</template>
