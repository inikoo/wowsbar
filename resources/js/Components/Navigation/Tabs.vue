<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 18 Mar 2023 04:04:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from "vue"
import { capitalize } from "@/Composables/capitalize"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faInfoCircle } from "@/../private/pro-solid-svg-icons"
import { faRoad, faClock, faDatabase } from "@/../private/pro-light-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'

library.add(faInfoCircle, faRoad, faClock, faDatabase)

const props = defineProps<{
    navigation: any
    current: string
}>()

defineEmits(['update:tab']);

let currentTab = ref(props.current);

const changeTab = (tabSlug: string) => {
    currentTab.value = tabSlug;
}

const tabIconClass = (current: string, type: string, align: string, extraClass: string) => {
    let iconClass = '-ml-0.5 h-5 w-5   ' + extraClass;
    iconClass += current ? '' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 ';
    iconClass += (type == 'icon' && align == 'right') ? 'ml-2 ' : 'mr-2 '
    return iconClass
}

</script>

<template>
    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
            <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                <option v-for="(tab, tabSlug) in navigation" :key="tabSlug" :selected="currentTab">{{ tab.title }}</option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200 dark:border-gray-500 flex">

                <!-- Left section -->
                <nav class="-mb-px flex grow space-x-6 ml-4" aria-label="Tabs">
                    <template v-for="(tab, tabSlug) in navigation" :key="tabSlug">
                        <div class="relative group">
                            <button
                                v-if="tab.align !== 'right'"
                                @click="[$emit('update:tab', tabSlug), changeTab(tabSlug)]"
                                :class="[
                                    'group inline-flex items-center py-2 px-1 border-b-2 font-medium text-sm']"
                                :aria-current="tabSlug === currentTab ? 'page' : undefined">
                                <FontAwesomeIcon v-if="tab.icon" :icon="tab.icon" :class="tabIconClass(tabSlug === currentTab, tab.type, tab.align, tab.iconClass ?? '')" aria-hidden="true"/>
                                <span v-if="tab.type !== 'icon'" class="capitalize">{{ tab.title }}</span>
                            </button>
                            <div class="absolute h-0.5 rounded-full -bottom-2 xl:bottom-0 left-[50%] translate-x-[-50%] mx-auto transition-all duration-200 ease-in-out"
                                :class="[tabSlug === currentTab ? 'bg-orange-500 dark:bg-gray-300 w-full' : 'bg-gray-400 w-0 group-hover:w-3/6']"
                            />
                        </div>
                    </template>
                </nav>

                <!-- Right section -->
                <nav class="flex flex-row-reverse mr-4" aria-label="Secondary Tabs">
                    <template v-for="(tab, tabSlug) in navigation" :key="tabSlug">
                        <button
                            v-if="tab.align === 'right'"
                            @click="[$emit('update:tab', tabSlug), changeTab(tabSlug)]"
                            :class="[tabSlug === currentTab ? 'border-orange-500 dark:border-gray-300 text-gray-600 dark:text-gray-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300',
                                'group inline-flex justify-center items-center py-2 px-2 border-b-2 font-medium text-sm']"
                            :aria-current="tabSlug === currentTab ? 'page' : undefined">
                            <FontAwesomeIcon :title="capitalize(tab.title)" v-if="tab.icon" :icon="tab.icon" class="h-5 w-5" aria-hidden="true"/>
                            <span v-if="tab.type!=='icon'" class="capitalize">{{ tab.title }}</span>
                        </button>
                    </template>
                </nav>
            </div>
        </div>
    </div>

    <!-- Backup purpose for FontAwesomeIcon on Right Section = :class="tabIconClass(tabSlug === currentTab, tab.type, tab.align, tab.iconClass??'')" -->
</template>
