<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:19:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe } from "@/../private/pro-light-svg-icons"
import TableBanners from "@/Pages/Tables/TableBanners.vue"
import Modal from '@/Components/Workshop/Modal/Modal.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { trans } from 'laravel-vue-i18n'

library.add(faGlobe)

defineProps<{
    pageHead: object
    title: string
    data: object
}>()

const isOpen = ref(false)
</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #modal="{ data: item }">
            <Button :style="item.style" @click="isOpen = true" class="capitalize" size="xs">{{ trans(item.label)}}</Button>
            <Modal :isOpen="isOpen" @onClose="() => isOpen = false">
                List of Websites
            </Modal>
        </template>
    </PageHeading>
    <TableBanners :data="data" />
</template>

