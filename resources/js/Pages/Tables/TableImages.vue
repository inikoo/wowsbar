<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 11:27:02 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import Image from "@/Components/Image.vue"
import { ref, watch } from 'vue'
import Checkbox from '@/Components/Checkbox.vue'

const props = defineProps<{
    data: object
    tab?: string
}>()

const emits = defineEmits<{
    (e: 'selectedRow', value: any): void
}>()

function imageRoute(image) {
    switch (route().current()) {
        case 'portfolio.images.index':
            return route(
                'portfolio.images.show',
                [image.slug])
    }
}
const selectedRow = ref([])

watch(selectedRow, () => {
    emits('selectedRow', selectedRow.value)
})
</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5" :selectedRow="selectedRow">
        <template #cell(slug)="{ item: image }">
            <Link :href="imageRoute(image)">
                {{ image['slug'] }}
            </Link>
        </template>
        <template #cell(thumbnail)="{ item: image }">
            <Image :src="image.thumbnail"/>
        </template>
        <template #cell(select)="{ item }">
            <Checkbox class="p-2.5" :value="item.id" name="select-image" id="select-image" v-model:checked="selectedRow"/>
        </template>
    </Table>


</template>


