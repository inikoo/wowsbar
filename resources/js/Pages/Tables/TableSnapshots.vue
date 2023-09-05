<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import Icon from "@/Components/Icon.vue";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faBroadcastTower, faSeedling} from "../../../private/pro-light-svg-icons";

library.add(faSeedling, faBroadcastTower);
const props = defineProps<{
    data: object
}>()

const formatDate = (dateIso: Date) => {
    const date = new Date(dateIso)
    const year = date.getFullYear()
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const day = date.getDate().toString().padStart(2, '0')

    const hours = date.getHours().toString()
    const minutes = date.getMinutes().toString()

    return date
}
</script>

<template>
    <Table :resource="data" class="mt-5" name="sht">
        <template #cell(published_at)="{ item: user }">
            {{ formatDate(user.published_at) }}
        </template>
        <template #cell(published_until)="{ item: user }">
            {{ formatDate(user.published_until) }}
        </template>
        <template #cell(state)="{ item: user }">
            <Icon :data="user.state"/>
        </template>
    </Table>
</template>
