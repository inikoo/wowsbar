<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 03:19:52 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, onMounted, onBeforeUnmount, Ref} from 'vue'
import Action from '@/Components/Forms/Fields/Action.vue'
import FieldForm from '@/Components/Forms/FieldForm.vue'
import { get as getLodash } from 'lodash'
import { capitalize } from "@/Composables/capitalize"
import { useLayoutStore } from '@/Stores/layout'

// import {jumpToElement} from "@/Composables/jumpToElement"
import {library} from "@fortawesome/fontawesome-svg-core"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {faGoogle} from "@fortawesome/free-brands-svg-icons"

import {
    faWindowClose,
    faToggleOn,
    faEdit,
    faUserLock,
    faBell,
    faCopyright,
    faUserCircle,
    faKey,
    faClone,
    faPaintBrush,
    faMoonStars,
    faLightbulbOn,
    faCheck,
    faPhone,
    faIdCard,
    faFingerprint,
    faLanguage,
    faAddressBook,
    faTrashAlt
} from '@fal/'

library.add(faToggleOn, faEdit, faUserLock, faBell, faCopyright, faUserCircle, faKey, faClone, faPaintBrush, faMoonStars, faLightbulbOn, faCheck, faPhone, faIdCard, faFingerprint, faLanguage, faAddressBook, faTrashAlt, faGoogle,
    faWindowClose)

const props = defineProps<{

    formData: {
        current:string,
        blueprint: {
            // sectionData
            label: string,
            title: string,
            subtitle?: string,
            icon: string
            fields: {
                // FieldData
                name: string,
                type: string,
                label: string,
                value: string | object
                icon?: string
                action?: {
                    data?: any
                    method?: string
                }
            }[]
            button: {
                title: string
                route: string
                disable: boolean
            }
        }[]
        args: {
            updateRoute: {
                name: string,
                parameters: string | string[]
            }
        }
        title?: string
    }
    appName: string
}>()

const layout = useLayoutStore()
const currentTab: Ref<string> = ref(props.formData.current)
const buttonRefs = ref([])  // For click linked to Navigation
const isMobile = ref(false)
const tabActive: any = ref({})

const updateViewportWidth = () => {
    isMobile.value = window.innerWidth <= 768
}

const handleIntersection = (element: Element, index: number) => (entries) => {
    const [entry] = entries;
    tabActive.value[`${index}`] = entry.isIntersecting;
}

onMounted(() => {
    updateViewportWidth()
    window.addEventListener('resize', updateViewportWidth)

    route().v().query ? currentTab == getLodash(route().v().query, 'section') : ''  // To auto open the navigation as the 'section' in url

    // To indicate active state that on viewport
    buttonRefs.value.forEach((element, index) => {
        const observer = new IntersectionObserver(handleIntersection(element, index));
        observer.observe(element);

        // Clean up the observer when the component is unmounted
        element.cleanupObserver = () => {
            observer.disconnect();
        };
    });

    // Clean up all the observers when the component is unmounted
    return () => {
        buttonRefs.value.forEach((button) => button.cleanupObserver());
    };
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateViewportWidth)
})

</script>


<template>
    <!-- If overflow-hidden, affect to Multiselect on Address -->
    <div class="rounded-lg shadow">
        <div v-if="!isMobile" class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x">

            <!-- Tab: Navigation -->
            <aside class="bg-gray-50/50 py-0 lg:col-span-3 lg:h-full">
                <div class="sticky top-16">
                    <template v-for="(item, key) in formData.blueprint">
                        <div @click="currentTab = key"
                            :class="[
                                key == currentTab
                                    ? `navigationSecondActive${capitalize(layout.appName)}`
                                    : `navigationSecond${capitalize(layout.appName)}`,
                                'cursor-pointer group border-l-4 px-3 py-2 flex items-center text-sm font-medium',
                            ]">
                            <FontAwesomeIcon v-if="item.icon" aria-hidden="true" class="flex-shrink-0 -ml-1 mr-2 h-4 w-4"
                                :class="[
                                    tabActive[key]
                                    ? 'text-gray-400 group-hover:text-gray-500'
                                    : 'text-gray-400',
                                ]"
                                :icon="item.icon" />
                            <span class="capitalize truncate">{{ item.label }}</span>
                            <!-- {{ tabActive }} -- {{ key == currentTab }} -->
                        </div>
                    </template>
                </div>
            </aside>


            <!-- Content of forms -->
            <div class="px-4 sm:px-6 md:px-4 col-span-9">
                <div v-for="(sectionData, sectionIdx ) in formData.blueprint" :key="sectionIdx" >
                    <div v-show="sectionIdx === currentTab" >
                        <div class="sr-only absolute -top-16" :id="`field${sectionIdx}`"/>
                        <!-- Title -->
                        <div class="flex items-center gap-x-2" ref="buttonRefs">
                            <h3 v-if="sectionData.title" class="text-lg leading-6 font-medium text-gray-700 capitalize">
                                {{ sectionData.title }}
                            </h3>
                            <p v-if="sectionData.subtitle" class="max-w-2xl text-sm text-gray-500">
                                {{ sectionData.subtitle }}
                            </p>
                        </div>

                        <div class="mt-2 pt-4 sm:pt-5 space-y-1">
                            <div v-for="(fieldData, fieldName, index) in sectionData.fields" :key="index" class="divide-y divide-green-200">
                                <dl class="divide-y divide-green-200  ">
                                    <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4">

                                        <!-- Field -->
                                        <dd class="sm:col-span-3">
                                            <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                                <div class="relative flex-grow">
                                                    <Action v-if="fieldData.type==='action'" :action="fieldData.action" :dataToSubmit="fieldData.action?.data" />
                                                    <FieldForm v-else :key="index" :field="fieldName" :fieldData="fieldData" :args="formData.args" :id="fieldData.name"/>
                                                </div>
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- For button Authorize Google -->
                <div class="py-2 px-3 flex justify-end max-w-2xl" v-if="formData.blueprint[currentTab].button" :id="formData.title">
                    <component :is="formData.blueprint[currentTab].button.disable ? 'div' : 'a'"
                        :href="formData.blueprint[currentTab].button.route" target="_blank" rel="noopener noreferrer"
                        class="px-3 py-1.5 rounded"
                        :class="[formData.blueprint[currentTab].button.disable ? 'bg-orange-200 cursor-default text-white' : 'text-gray-100 bg-orange-500 hover:bg-orange-600']"
                    >
                        {{ formData.blueprint[currentTab].button.title }}
                    </component>
                </div>
            </div>

        </div>

        <!-- Mobile view -->
        <ul v-else class="space-y-8">
            <li v-for="(item, key) in formData.blueprint"
                class="group font-medium"
                :aria-current="key === currentTab ? 'page' : undefined"
            >
                <div class="bg-gray-200 py-3 pl-5 flex items-center">
                    <FontAwesomeIcon v-if="item.icon" aria-hidden="true" :icon="item.icon"
                        class="flex-shrink-0 mr-3 h-5 w-5"
                        :class="[
                            key === currentTab ? 'text-gray-400' : 'text-gray-500',
                        ]"/>
                    <span class="capitalize truncate">{{ item.title }}</span>
                </div>
                <div class="px-5">
                    <div v-for="(fieldData, fieldName, index) in formData.blueprint[key].fields" class="py-4">
                        <Action v-if="fieldData.type === 'action'" :action="fieldData.action" :dataToSubmit="fieldData.action?.data" />
                        <FieldForm v-else :key="index" :field="fieldName" :fieldData="fieldData" :args="formData.args" :id="fieldData.name" />
                    </div>
                </div>
            </li>
        </ul>

    </div>
    <!-- <pre>{{ getLodash(route().v().query, 'section')  }} -- {{ route().v().query }}</pre> -->
</template>



