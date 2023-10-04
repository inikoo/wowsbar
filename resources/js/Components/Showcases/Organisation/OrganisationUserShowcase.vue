
<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 04 Oct 2023 16:39:35 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { useLayoutStore } from "@/Stores/layout"

const props = defineProps<{
    data: any
}>()

const layout = useLayoutStore()

</script>

<template>
    <div class="my-3 mx-4">
        <div class="rounded-md bg-gray-200 w-fit relative shadow">


        </div>
    </div>
</template>

<style scoped>

</style>
