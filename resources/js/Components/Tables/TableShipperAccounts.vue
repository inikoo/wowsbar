<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 07 Oct 2023 21:49:18 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue';

const props = defineProps<{
    data: object,
    tab?: string
}>()

</script>

<template>
    <Table :resource="data" :name="'shipper_accounts'" class="mt-5">
        <template #cell(event_address)="{ item: shipperAccount }">
            {{ shipperAccount['label'] }}
        </template>
    </Table>
</template>
