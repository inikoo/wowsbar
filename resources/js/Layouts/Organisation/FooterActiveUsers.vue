<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 11:19:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { ref } from 'vue'
import { useLayoutStore } from "@/Stores/layout"
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { faBriefcase} from '@fal';
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { getDataFirebase } from '@/Composables/firebase'
import { watchEffect } from 'vue'
// import moment from "moment";

library.add(faBriefcase);

const props = defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive'): void
}>()

const layout = useLayoutStore()

const dbPath = 'org/active_users'

const activeUsersData = ref(getDataFirebase(dbPath))
const activeUserData = ref()
const activeUserDataLength = ref()

watchEffect(() => {
    activeUserData.value = activeUsersData.value
    activeUserDataLength.value = activeUserData.value ? Object.keys(activeUserData.value).length : 0
    layout.rightSidebar.activeUsers.users = activeUserData.value
    layout.rightSidebar.activeUsers.count = activeUserDataLength.value
})

function getAwayStatus(lastActive)
{
    // lastActive = moment(lastActive);
    // let now = moment();

    // return Boolean(now.diff(lastActive));
    return 'moment jsss'
}

</script>

<template>

    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-800"
        :class="[
            isTabActive == 'activeUsers'
                ? layout.systemName === 'org' ? 'bg-gray-200 text-gray-700' : 'text-gray-300'
                : 'hover:bg-gray-200'
        ]"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
        <div class="relative text-xs flex items-center gap-x-1">
            <div class="ring-1 h-2 aspect-square rounded-full" :class="[activeUserDataLength > 0 ? 'animate-pulse bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">{{ trans('Active Users') }} ({{ activeUserDataLength ?? 0 }})</span>
        </div>
        <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
            <template #default>
                <div v-if="activeUserDataLength" v-for="(dataUser, index) in activeUserData" class="flex justify-start py-1 px-2 gap-x-1.5 cursor-default"

                >
                    <!-- <img :src="`/media/${user.user.avatar_thumbnail}`" :alt="user.user.contact_name" srcset="" class="h-4 rounded-full shadow"> -->
                    <span class="font-semibold text-gray-700">{{ dataUser.id }}</span> -
                    <!-- <FontAwesomeIcon
                        v-if="dataUser.route.icon"
                        class="flex-shrink-0 h-3 w-3 mr-1 opacity-80"
                        :icon="'fal fa-'+dataUser.route.icon"
                        aria-hidden="true" /> -->
                    <span v-if="dataUser.loggedIn" class="text-gray-800">{{ dataUser.route?.name ? trans(dataUser.route.label ?? '') : '' }}</span>
                    <span v-else-if="getAwayStatus(dataUser.last_active)" class="text-gray-800">{{ getAwayStatus(dataUser.last_active) ? 'Away' : '' }}</span>
                    <!-- <span v-if="dataUser.route.subject" class="capitalize text-gray-300">{{ dataUser.route.subject }}</span> -->
                </div>
            </template>
        </FooterTab>
    </div>
</template>
