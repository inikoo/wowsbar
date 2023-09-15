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

// import {ref,computed} from 'vue'

// import TableElements from '@/Components/Table/TableElements.vue'

const props = defineProps<{
    data: object
}>()

// console.log('dddd', props.data)
</script>

<template>
    <Table :resource="data" class="mt-5" name="hst">
        <template #cell(old_values)="{ item: user }">
            <JsonViewer :value="user['old_values']" copyable sort>
            </JsonViewer>
        </template>

        <template #cell(new_values)="{ item: user }">
            <JsonViewer :value="user['new_values']" copyable sort>
            </JsonViewer>
        </template>

        <template #cell(datetime)="{ item: user }">
            {{ useFormatTime(user.datetime, locale.language.code, true) }}
        </template>
    </Table>
</template>
