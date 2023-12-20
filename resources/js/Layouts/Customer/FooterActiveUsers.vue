<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 10:37:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from "@/Stores/layout"
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { liveCustomerUsers } from '@/Stores/active-users-customer'
import { useTruncate } from '@/Composables/useTruncate'

// library.add(faBriefcase);

const props = defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive', value: boolean | string): void
}>()

const layout = useLayoutStore()

</script>

<template>

    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-300"
        :class="[isTabActive == 'activeUsers' ? 'bg-gray-700 text-gray-300' : 'text-gray-300 hover:bg-gray-600']"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
        <div class="relative text-xs flex items-center gap-x-1">
            <div class="ring-1 h-2 aspect-square rounded-full" :class="[liveCustomerUsers().count > 0 ? 'animate-pulse bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">{{ trans('Active Users') }} ({{ liveCustomerUsers().count ?? 0 }})</span>
        </div>
        <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
            <template #default>
                <div v-for="(dataUser, index) in liveCustomerUsers().liveCustomerUsers" class="flex justify-start py-1 px-2 gap-x-1.5 hover:bg-gray-700 cursor-default">
                    <span v-if="dataUser?.name" class="capitalize font-semibold whitespace-nowrap" v-tooltip="dataUser.name">{{ useTruncate(dataUser.name, 10) }}</span>
                    <span v-else class="capitalize text-gray-400">Unknown</span>
                    <span v-if="dataUser.current_page?.label" class="capitalize whitespace-nowrap">- {{ dataUser?.current_page?.label }}</span>
                    <span v-else class="capitalize text-gray-400 italic">- Unknown</span>
                </div>
            </template>
        </FooterTab>
    </div>
</template>
