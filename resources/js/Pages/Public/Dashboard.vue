<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 10:29:40 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import Input from '@/Components/Forms/Fields/Input.vue'

const props = defineProps<{
    title: string,
}>()

const fieldData = {
    type: "input",
        label: "google redirect URI",
        value: "test",
        readonly: true,
        copyButton: true
}
</script>

<template layout="PublicApp">
    <Head :title="capitalize(title)" />
    <div class="max-w-2xl lg:mx-0 lg:max-w-none">
    <Input class="pt-4 sm:pt-5 px-6" :form="{name:'test'}" fieldName="name"
    :fieldData="fieldData"/>
</div>
</template>
