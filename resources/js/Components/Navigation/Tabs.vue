<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 18 Mar 2023 04:04:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref } from "vue"
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from '@/Stores/layout'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faInfoCircle, faTachometerAlt } from '@fas/'
import {
    faAd,
    faBullseye,
    faClock,
    faDatabase, faEnvelopeOpenText,
    faNewspaper, faPaperPlane,
    faRoad,
    faTransporter, faShoppingCart,
    faWallet, faSign, faUserCircle
} from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faGoogle } from "@fortawesome/free-brands-svg-icons"

library.add(faInfoCircle, faTachometerAlt, faRoad, faWallet, faSign, faClock, faUserCircle, faDatabase, faGoogle, faTransporter, faShoppingCart, faBullseye, faNewspaper, faPaperPlane, faAd, faEnvelopeOpenText)

const props = defineProps<{
    navigation: any
    current: string
}>()

defineEmits(['update:tab']);

const layout = useLayoutStore()

let currentTab: Ref<any> = ref(props.current);

const changeTab = (tabSlug: any) => {
    currentTab.value = tabSlug;
}

const tabIconClass = (current: string | boolean, type: string, align: string, extraClass: string) => {
    let iconClass = '-ml-0.5 h-5 w-5   ' + extraClass;
    iconClass += current ? '' : 'text-gray-400 group-hover:text-gray-500 ';
    iconClass += (type == 'icon' && align == 'right') ? 'ml-2 ' : 'mr-2 '
    return iconClass
}

</script>

<template>
    <div>
        <!-- Tabs: Mobile view -->
        <div class="sm:hidden px-3 pt-2">
            <label for="tabs" class="sr-only">Select a tab</label>
            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
            <select id="tabs" name="tabs" class="block w-full capitalize rounded-md border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                @input="(val: any) => { $emit('update:tab', val.target.value), changeTab(val.target.value) }"
            >
                <option v-for="(tab, tabSlug) in navigation" :key="tabSlug" :selected="tabSlug == currentTab" :value="tabSlug" class="capitalize">{{ tab.title }}</option>
            </select>
        </div>

        <!-- Tabs: Large view -->
        <div class="hidden sm:block">
            <div class="border-b border-gray-200 flex text-gray-500 pr-4">

                <!-- Left section -->
                <nav class="flex grow space-x-6 ml-4" aria-label="Tabs">
                    <template v-for="(tab, tabSlug) in navigation" :key="tabSlug">
                        <div v-if="tab.align !== 'right'" class="relative group">
                            <button
                                :id="tab.title.replace(' ','-')"
                                @click="[$emit('update:tab', tabSlug), changeTab(tabSlug)]"
                                :class="[
                                    'group inline-flex items-center py-2 px-1 font-medium text-sm']"
                                :aria-current="tabSlug === currentTab ? 'page' : undefined">
                                <FontAwesomeIcon v-if="tab.icon" :icon="tab.icon" :class="tabIconClass(tabSlug === currentTab, tab.type, tab.align, tab.iconClass ?? '')" aria-hidden="true"/>
                                <span v-if="tab.type !== 'icon'" class="capitalize">
                                    {{ trans(tab.title) }}
                                    <slot name="addTitle" :tabSlug="tabSlug">

                                    </slot>
                                </span>
                            </button>
                            <div class="" :class="[tabSlug === currentTab ? `bottomNavigationActive${capitalize(layout.systemName)}` : `bottomNavigation${capitalize(layout.systemName)}`]" />
                        </div>
                    </template>
                </nav>

                <!-- Right section -->
                <nav class="flex flex-row-reverse" aria-label="Secondary Tabs">
                    <slot name="content">
                        <template v-for="(tab, tabSlug, index) in navigation" :key="tabSlug">
                            <div class="relative group">
                                <button
                                    :id="tab.title"
                                    v-if="tab.align === 'right'"
                                    @click="[$emit('update:tab', tabSlug), changeTab(tabSlug)]"
                                    :class="['group inline-flex justify-center items-center py-2 px-2 font-medium text-sm']"
                                    :aria-current="tabSlug === currentTab ? 'page' : undefined">
                                    <FontAwesomeIcon :title="capitalize(tab.title)" v-if="tab.icon" :icon="tab.icon" class="h-5 w-5" aria-hidden="true"/>
                                    <span v-if="tab.type!=='icon'" class="capitalize">{{ trans(tab.title) }}</span>
                                </button>
                                <div :class="[tabSlug === currentTab ? `bottomNavigationActive${capitalize(layout.systemName)}` : `bottomNavigation${capitalize(layout.systemName)}`]" />
                            </div>
                        </template>
                    </slot>
                </nav>
            </div>
        </div>
    </div>
</template>
