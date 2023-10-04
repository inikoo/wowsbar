<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 04 Oct 2023 08:09:05 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import Image from "@/Components/Image.vue"
import { ref, watch, reactive } from 'vue'
import Checkbox from '@/Components/Checkbox.vue'
import Modal from '@/Components/Utils/Modal.vue'
import CropImage from '@/Components/Workshop/CropImage/CropImage.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { trans } from 'laravel-vue-i18n'
import { ulid } from 'ulid'
import { useFormatTime } from '@/Composables/useFormatTime'
import { useLocaleStore } from '@/Stores/locale'

const locale = useLocaleStore()

const props = defineProps<{
    data: any
    tab?: string
    isSelectImage: boolean
}>()

const emits = defineEmits<{
    (e: 'selectedRow', value: any): void
}>()

function imageRoute(image) {
    switch (route().current()) {
        case 'customer.banners.gallery':
            return route(
                'customer.banners.gallery.stock-images.show',
                [image.slug])
    }
}

const selectedRow = reactive({
    [props.tab]: []
})

watch(selectedRow, () => {
    emits('selectedRow', selectedRow)
})

const isOpen = ref(false)
const addFiles = ref([])

const closeModal = () => {
    addFiles.value.files = null
    isOpen.value = false
}

const addComponent = async (element: any) => {
    addFiles.value = element.target.files
    isOpen.value = true
}


</script>

<template>
    <Table :resource="data" :name="tab" :selectedRow="selectedRow" :key="props.data.data.length">


        <!-- Column: Name -->
        <template #cell(name)="{ item: image }">
            <Link :href="imageRoute(image)">
                {{ image['name'] }}
            </Link>
        </template>

        <!-- Column: image thumbnail -->
        <template #cell(thumbnail)="{ item: image }">
            <Image :src="image.thumbnail" class="shadow"/>
        </template>

        <template #cell(created_at)="{ item: image }">
            {{ useFormatTime(image['created_at'], locale.language.code) }}
        </template>

        <!-- Column: select item -->
        <template #cell(select)="{ item, tabName }">
            <Checkbox v-if="isSelectImage" class="p-2.5" :value="item.id" name="select-image" id="select-image" v-model:checked="selectedRow[tabName]"/>
        </template>
    </Table>
</template>
