<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 11:27:02 Malaysia Time, Sanur, Bali
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
                'customer.banners.images.show',
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

const uploadImageRespone = (res: any) => {
    props.data.data.push(...res.data)
    let setData = []
    for (const set of res.data) {
        setData.push({
            id: null,
            ulid: ulid(),
            layout: {
                imageAlt: set.name,
            },
            image: set,
            visibility: true
        })
    }
    const newFiles = [...setData]
    isOpen.value = false
}
</script>

<template>
    <Table :resource="data" :name="tab" :selectedRow="selectedRow" :key="props.data.data.length">
        <!-- Button Upload Files -->
        <template #uploadFile="{item}">
            <Button :style="`primary`" icon="fas fa-plus" class="relative">
                {{ trans(item.label) }}
                <label
                    class="bg-transparent inset-0 absolute cursor-pointer"
                    id="addFilesLabel" for="addFiles"
                />
                <input type="file" multiple name="file" id="addFiles"
                    @change="addComponent" accept="image/*"
                    class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
            </Button>

            <Modal :isOpen="isOpen" @onClose="closeModal">
                <div>
                    <CropImage :data="addFiles" :imagesUploadRoute="item.route" :response="uploadImageRespone"/>
                </div>
            </Modal>
        </template>

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
