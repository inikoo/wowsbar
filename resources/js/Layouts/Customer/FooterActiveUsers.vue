<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 10:37:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from "@/Stores/layout"
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { faBriefcase} from '@fal'
import { library } from "@fortawesome/fontawesome-svg-core"
// import { useCustOnlineUsers } from '@/Stores/cust-online-users'
// import { useTruncate } from '@/Composables/useTruncate'
// import { Link } from '@inertiajs/vue3'
// import Image from '@/Components/Image.vue'

library.add(faBriefcase);

const props = defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive', value: string | boolean): void
}>()

import { getDataFirebase } from '@/Composables/firebase'
const dbPath = 'customers/' + useLayoutStore().user.customer.ulid + '/active_users'
getDataFirebase(dbPath)

</script>

<template>

    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-300"
        :class="[isTabActive == 'activeUsers' ? 'bg-gray-700 text-gray-300' : 'text-gray-300 hover:bg-gray-600']"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
        <!-- <div class="relative text-xs flex items-center gap-x-1">
            <div class="ring-1 h-2 aspect-square rounded-full" :class="[dataCustomerLength > 0 ? 'animate-pulse bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">{{ trans('Active Users') }} ({{ dataCustomerLength ?? 0 }})</span>
        </div>
        <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
            <template #default>
                <div v-if="dataCustomerLength" v-for="(dataUser, index) in compUserOnline" class="flex justify-start py-1 px-2 gap-x-1.5 hover:bg-gray-700 cursor-default">
                    <span class="font-semibold" :class="[getStatusOnline(dataUser) ? 'text-gray-100' : 'text-gray-400']">{{ dataUser.id }}</span> -
                    <span v-if="dataUser.loggedIn" class="" :class="[getStatusOnline(dataUser) ? 'text-gray-300' : 'text-gray-400']">{{ getStatusOnline(dataUser) ? dataUser.route.name : 'Away' }}</span>
                </div>
            </template>
        </FooterTab> -->
    </div>
</template>
