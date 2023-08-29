<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { ref } from 'vue'
import { useLayoutStore } from "@/Stores/layout"
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { faBriefcase} from "@/../private/pro-light-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { getDataFirebase } from '@/Composables/firebase'
import { watchEffect } from 'vue'

library.add(faBriefcase);

const props = defineProps<{
    isTabActive: string | boolean
    appScope: string
}>()

defineEmits<{
    (e: 'isTabActive'): void
}>()

const layout = useLayoutStore()

const dbPath = props.appScope+'/'+layout.tenant.code+'/active_users'

const getDataTenant = ref(getDataFirebase(dbPath))
const dataTenant = ref()
const dataTenantLength = ref()

watchEffect(() => {
    dataTenant.value = getDataTenant.value
    // console.log("========================")
    // console.log(dataTenant.value)
    dataTenantLength.value = dataTenant.value ? Object.keys(dataTenant.value).length : 0
    layout.rightSidebar.activeUsers.users = dataTenant.value
    layout.rightSidebar.activeUsers.count = dataTenantLength.value
})
</script>

<template>

    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-300"
        :class="[isTabActive == 'activeUsers' ? 'bg-gray-700' : '']"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
        <div class="relative text-xs flex items-center gap-x-1">
            <div class="ring-1 h-2 aspect-square rounded-full" :class="[dataTenantLength > 0 ? 'animate-pulse bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">{{ trans('Active Users') }} ({{ dataTenantLength ?? 0 }})</span>
        </div>
        <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
            <template #default>
                <div v-if="dataTenantLength" v-for="(dataUser, index) in dataTenant" class="flex justify-start py-1 px-2 gap-x-1.5 hover:bg-gray-700 cursor-default">
                    <!-- <img :src="`/media/${user.user.avatar_thumbnail}`" :alt="user.user.contact_name" srcset="" class="h-4 rounded-full shadow"> -->
                    <p class="text-left text-gray-100">
                        <span class="font-semibold text-gray-100">{{ dataUser.id }}</span> -
                        <!-- <FontAwesomeIcon
                            v-if="dataUser.route.icon"
                            class="flex-shrink-0 h-3 w-3 mr-1 opacity-80"
                            :icon="'fal fa-'+dataUser.route.icon"
                            aria-hidden="true" /> -->
                        <span v-if="dataUser.loggedIn" class="text-gray-300">{{ dataUser.route?.name ? trans(dataUser.route.name) : '' }}</span>
                        <span v-else class="text-gray-300">Logged Out</span>
                        <!-- <span v-if="dataUser.route.subject" class="capitalize text-gray-300">{{ dataUser.route.subject }}</span> -->
                    </p>
                </div>
            </template>
        </FooterTab>
    </div>
</template>
