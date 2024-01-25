<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 11:19:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from "@/Stores/layout"
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { faBriefcase} from '@fal'
import { library } from "@fortawesome/fontawesome-svg-core"
import { liveOrganisationUsers } from '@/Stores/active-users'
import { useTruncate } from '@/Composables/useTruncate'
import { Link } from '@inertiajs/vue3'
import Image from '@/Components/Image.vue'

library.add(faBriefcase);

const props = defineProps<{
    isTabActive: string | boolean
}>()

defineEmits<{
    (e: 'isTabActive', value: string | boolean): void
}>()

const layout = useLayoutStore()

</script>

<template>
    <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-800"
        :class="[
            isTabActive == 'activeUsers'
                ? layout.systemName === 'org' ? 'bg-white text-gray-700' : 'text-gray-300'
                : layout.systemName === 'org' ? '' : 'text-gray-300 hover:bg-gray-600',
        ]"
        @click="isTabActive == 'activeUsers' ? $emit('isTabActive', !isTabActive) : $emit('isTabActive', 'activeUsers')"
    >
        <!-- Tab -->
        <div class="relative text-xs flex items-center gap-x-1">
            <div class="ring-1 h-2 aspect-square rounded-full" :class="[liveOrganisationUsers().count> 0 ? 'animate-pulse bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
            <span class="">{{ trans('Active users') }} ({{ liveOrganisationUsers().count ?? 0 }})</span>
        </div>
        
        <!-- Content -->
        <Transition name="slide-to-up">
            <FooterTab @pinTab="() => $emit('isTabActive', false)" v-if="isTabActive == 'activeUsers'"
                :tabName="`activeUsers`">
                <template #default>
                    <Link v-for="(dataUser, index) in liveOrganisationUsers().liveOrganisationUsers" :href="dataUser.current_page?.url || '#'" class="flex items-center py-1 px-2 gap-x-1.5"
                        :class="dataUser.id == layout.user.id ? 'bg-slate-700/20' : 'hover:bg-slate-700/10'">
                        <Image v-if="dataUser.avatar_thumbnail?.original" :src="dataUser.avatar_thumbnail" :alt="dataUser.username" class="h-5 aspect-square rounded-full shadow overflow-hidden" />
                        <div v-else class="h-5 aspect-square rounded-full overflow-hidden skeleton" />
                        
                        <p class="flex flex-col items-start">
                            <span class="capitalize text-xs font-bold">{{ dataUser?.username }}</span>

                            <span v-if="dataUser.current_page?.label" class="capitalize opacity-70">{{ useTruncate(dataUser?.current_page?.label, 17) }}</span>
                            <span v-else class="capitalize text-gray-500 italic opacity-60">Unknown</span>
                        </p>
                        <!-- <span v-if="dataUser.loggedIn" class="text-gray-800">{{ dataUser.route?.name ? trans(dataUser.route.label ?? '') : '' }}</span>
                            <span v-else-if="getAwayStatus(dataUser.last_active)" class="text-gray-800">{{ getAwayStatus(dataUser.last_active) ? 'Away' : '' }}</span> -->
                        <!-- <span v-if="dataUser.route.subject" class="capitalize text-gray-300">{{ dataUser.route.subject }}</span> -->
                    </Link>
                </template>
            </FooterTab>
        </Transition>
    </div>
</template>
