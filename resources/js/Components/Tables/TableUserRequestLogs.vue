<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
// import { User } from "@/types/user";
import AddressLocation from "@/Components/Elements/Info/AddressLocation.vue"
import UserAgent from "@/Components/Elements/Info/UserAgent.vue"
import { useFormatTime } from "@/Composables/useFormatTime"
import { useLocaleStore } from "@/Stores/locale"

const locale = useLocaleStore()

const props = defineProps<{
    data: object
}>()

</script>

<template>
    <Table :resource="data" class="mt-5">
        <template #cell(username)="{ item: user }">
            <template v-if="user.username">
                <span class="text-gray-500">{{ user.username }}</span>
            </template>
        </template>
        
        <template #cell(user_agent)="{ item: user }">
            <UserAgent :data="user.user_agent" />
        </template>

        <template #cell(location)="{ item: user }">
            <AddressLocation :data="user.location"/>
        </template>

        <template #cell(datetime)="{ item: user }">
            <span class="text-gray-500">{{ useFormatTime(user.datetime, { localeCode: locale.language.code, formatTime: 'hms' }) }}</span>
        </template>
    </Table>
</template>
