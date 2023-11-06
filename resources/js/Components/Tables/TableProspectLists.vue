<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue';
import {useLocaleStore} from "@/Stores/locale";

const props = defineProps<{
    data: object,
    tab?: string
}>()
const locale = useLocaleStore()



</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(number_items)="{ item: prospect_list }">
            {{locale.number(prospect_list['number_items'])}}
        </template>
    </Table>
</template>


