<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import JsonViewer from 'vue-json-viewer'
import { useFormatTime } from '@/Composables/useFormatTime'
import { useLocaleStore } from '@/Stores/locale'
const locale = useLocaleStore()

const props = defineProps<{
    data: object,
    tab?: string
}>()

</script>

<template>
    <Table :resource="data" :name="tab"  class="mt-5"  >

        <template #cell(created_at)="{ item: history }">
            {{ useFormatTime(history['created_at'], locale.language.code, true) }}
        </template>

        <template #cell(old_values)="{ item: history }">
            <JsonViewer :value="history['old_values']" copyable sort>
            </JsonViewer>
        </template>

        <template #cell(new_values)="{ item: history }">
            <JsonViewer :value="history['new_values']" copyable sort>
            </JsonViewer>
        </template>

        <template #cell(datetime)="{ item: history }">
            {{ useFormatTime(history.datetime, locale.language.code, true) }}
        </template>

        <template #cell(action)="{ item: history }">
            {{history['natural_language']}}
        </template>

    </Table>
</template>
