<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 14:02:24 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
// import { useLocaleStore } from "@/Stores/locale"
import { useLayoutStore } from "@/Stores/layout"
import { liveOrganisationUsers } from '@/Stores/active-users'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faTimes)


type UserOnline = {
    id: string
    is_active: boolean
    last_active: string
    route: object
    user: {
        avatar_id: number
        contact_name: string
        username: string
    }
}
const layout = useLayoutStore()

</script>

<template>
    <div class="bg-gray-100 text-xs h-full border-l border-gray-200 space-y-4">
        <TransitionGroup name="list" tag="ul">
            <!-- Online Users -->
            <li v-if="layout.rightSidebar.activeUsers.show" class="px-2 py-2" key="1">
                <div class="pl-2 pr-1.5 bg-slate-300/80 text-slate-700 text-xs font-semibold rounded flex justify-between leading-none">
                    <span class="py-1">Active Users</span>
                    <div @click="layout.rightSidebar.activeUsers.show = false" class="flex justify-center items-center cursor-pointer px-1.5 text-slate-400 hover:text-slate-600">
                        <FontAwesomeIcon icon='fal fa-times' class='' aria-hidden='true' />
                    </div>
                </div>

                <!-- Looping: user list -->
                <div v-for="(user, index) in liveOrganisationUsers().liveOrganisationUsers" class="pl-2.5 pr-1.5 flex justify-start items-center py-1 gap-x-2.5 cursor-default">
                    <p class="text-gray-600 flex items-center gap-y-0.5 gap-x-1">
                        <span class="text-gray-700 leading-none capitalize font-semibold">{{ user?.name }}</span>
                        <span class="leading-none">-</span>
                        <span class="text-gray-500 whitespace-normal leading-none text-[10px] capitalize">{{ user?.current_page?.label ?? 'Unknown' }}</span>
                    </p>
                </div>
            </li>
        </TransitionGroup>

        <!-- Add new here -->
    </div>
</template>
