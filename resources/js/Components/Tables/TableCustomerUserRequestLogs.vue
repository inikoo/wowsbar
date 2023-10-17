<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 11 Oct 2023 17:30:41 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
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


        <template #cell(user_agent)="{ item: user }">
            <UserAgent :data="user.user_agent" />
        </template>

        <template #cell(location)="{ item: user }">
            <AddressLocation :data="user.location"/> <span class="ml-2 opacity-75">({{user.ip_address}})</span>
        </template>

        <template #cell(datetime)="{ item: user }">
            {{ useFormatTime(user.datetime, locale.language.code, 'hms') }}
        </template>
    </Table>
</template>
