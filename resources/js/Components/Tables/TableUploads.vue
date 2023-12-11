<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import { Website } from "@/types/website";
import { useFormatTime } from "@/Composables/useFormatTime"
import { useLocaleStore } from "@/Stores/locale"
import Tag from '../Tag.vue';

const props = defineProps<{
    data: object,
    tab?: string
}>()

const locale = useLocaleStore()


</script>
  
<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(errors)="{ item }">
            <span class="text-red-500">{{ item.errors[0] }}</span>
        </template>
        <template #cell(updated_at)="{ item }">
            <span 
                class="text-gray-500">
                {{ 
                    useFormatTime(item.updated_at, {localeCode: locale.language.code, formatTime:'hms'}) 
                }}
            </span>
        </template>
        <template #cell(created_at)="{ item }">
            <span 
                class="text-gray-500">
                    {{ useFormatTime(item.created_at, {localeCode: locale.language.code, formatTime:'hms'})}}
            </span>
        </template>
        <template #cell(status)="{ item }">
            <Tag :theme="item.status == 'failed' ? 7 : 3" :label="item.status" />
        </template>
    </Table>
</template>
  
  
  