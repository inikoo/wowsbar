<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 03:19:52 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import Action from '@/Components/Forms/Fields/Action.vue'

import FieldForm from '@/Components/Forms/FieldForm.vue'
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faGoogle} from "@fortawesome/free-brands-svg-icons"

import { faToggleOn,faEdit,faUserLock,faBell,faCopyright, faUserCircle, faKey, faClone, faPaintBrush, faMoonStars, faLightbulbOn, faCheck, faPhone, faIdCard, faFingerprint,faLanguage,faAddressBook,faTrashAlt } from "@/../private/pro-light-svg-icons"

library.add(faToggleOn,faEdit,faUserLock,faBell,faCopyright,faUserCircle, faKey, faClone, faPaintBrush, faMoonStars, faLightbulbOn, faCheck, faPhone, faIdCard, faFingerprint,faLanguage,faAddressBook,faTrashAlt, faGoogle)

const props = defineProps<{

    formData: {
        blueprint: Array<{
            title: string
            icon: string
            current: boolean
            fields: Array<
                {
                    name: string,
                    type: string,
                    label: string,
                    value: string | object
                    icon?: string
                }
            >
            button: {
                title: string
                route: string
                disable: boolean
            }
        }>,
        args: {
            updateRoute: {
                name: string,
                parameters: string | string[]
            }
        }
        title?: string
    }
}>()


const current = ref(0)

const isMobile = ref(false)

const updateViewportWidth = () => {
    isMobile.value = window.innerWidth <= 768
}

onMounted(() => {
    updateViewportWidth()
    window.addEventListener('resize', updateViewportWidth)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateViewportWidth)
})

</script>


<template>
    <!-- If overflow-hidden, affect to Multiselect on Address -->
    <div class="rounded-lg shadow">
        <div v-if="!isMobile" class="divide-y divide-gray-200 dark:divide-gray-500 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x">

            <!-- Tab: Navigation -->
            <aside class="py-0 lg:col-span-3 lg:h-full">
                <nav role="navigation" class="space-y-1">
                    <ul>
                        <li v-for="(item, key) in formData['blueprint']" @click="current = key" :class="[
                            key == current
                                ? 'tabNavigationActive dark:text-gray-300'
                                : 'tabNavigation dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-700',
                            'cursor-pointer group px-3 py-2 flex items-center text-sm font-medium',
                            ]" :aria-current="key === current ? 'page' : undefined">
                            <FontAwesomeIcon v-if="item.icon" aria-hidden="true" :class="[
                                key === current ? 'text-gray-400' : 'text-gray-500',
                                'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                            ]" :icon="item.icon" />

                            <span class="capitalize truncate">{{ item.title }}</span>
                        </li>
                    </ul>
                </nav>
            </aside>


            <!-- Content of forms -->
            <div class="px-4 sm:px-6 md:px-4 col-span-9">
                <div class="divide-y divide-gray-200 dark:divide-gray-500 flex flex-col">
                    <div class=" pt-4 sm:pt-5 px-6 " v-for="(fieldData, field ) in formData.blueprint[current].fields">
                        <!-- If the type is 'action' -->
                        <div v-if="fieldData.type === 'action'" class="flex justify-center">
                            <Action :fieldData="fieldData" :key="field">
                            </Action>
                        </div>

                        <!-- If the type is 'Input', 'Select', 'Radio', etc -->
                        <FieldForm v-else :key="field" :field="field" :fieldData="fieldData" :args="formData.args" :id="fieldData.name"/>
                    </div>


                    <div class="py-2 px-3 flex justify-end max-w-2xl" v-if="formData.blueprint[current].button"  :id="formData.title">
                        <component :is="formData.blueprint[current].button.disable ? 'div' : 'a'"
                            :href="formData.blueprint[current].button.route" target="_blank" rel="noopener noreferrer"
                            class="px-3 py-1.5 rounded"
                            :class="[formData.blueprint[current].button.disable ? 'bg-orange-200 cursor-default text-white' : 'text-gray-100 bg-orange-500 hover:bg-orange-600']"
                        >
                            {{ formData.blueprint[current].button.title }}
                        </component>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile view -->
        <div v-else class="">
            <ul class="space-y-8">
                <li v-for="(item, key) in formData['blueprint']" class="group font-medium" :aria-current="key === current ? 'page' : undefined">
                    <div class="bg-gray-200 py-2 pl-5 flex items-center">
                        <FontAwesomeIcon v-if="item.icon" aria-hidden="true" :icon="item.icon"
                            :class="[
                                key === current ? 'text-gray-400' : 'text-gray-500',
                                'flex-shrink-0 mr-3 h-5 w-5',
                            ]" />
                        <span class="capitalize truncate">{{ item.title }}</span>
                    </div>
                    <div class="pl-5">
                        <!-- <FieldForm class=" pt-4 sm:pt-5 px-6 " v-for="(fieldData, field ) in formData.blueprint[key].fields"
                        :key="field" :field="field" :fieldData="fieldData" :args="formData.args" :id="fieldData.name"/> -->
                        <!-- aaaaaa -->
                        <component :is="fieldData.type === 'action' ? Button : FieldForm" class=" pt-4 sm:pt-5 px-6 " v-for="(fieldData, field ) in formData.blueprint[key].fields"
                        :key="field" :field="field" :fieldData="fieldData" :args="formData.args" :id="fieldData.name"/>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</template>



