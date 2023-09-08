<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import Icon from "@/Components/Icon.vue"
import {library} from "@fortawesome/fontawesome-svg-core"
import { faBroadcastTower, faSeedling } from "@/../private/pro-light-svg-icons"
import { useFormatTime } from "@/Composables/useFormatTime"
import { useLocaleStore } from '@/Stores/locale'
const locale = useLocaleStore()


library.add(faSeedling, faBroadcastTower);
const props = defineProps<{
    data: object
}>()

</script>

<template>
    <Table :resource="data" class="mt-5" name="sht">
        <template #cell(published_at)="{ item: user }">
            {{ useFormatTime(user.published_at, locale.language.code, true) }}
        </template>

        <template #cell(published_until)="{ item: user }">
            {{ useFormatTime(user.published_until, locale.language.code, true) }}
        </template>

        <template #cell(state)="{ item: user }">
            <Icon :data="user.state"/>
        </template>
    </Table>
</template>
