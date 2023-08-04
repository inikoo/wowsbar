<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Link } from "@inertiajs/vue3";

const props = defineProps<{
    whatsNew: {
        label: string
        route: {
            name: string
            parameters: string
        }
    }
}>()
</script>

<template>
    <Head title="Homepage" />
    <div>Hello</div>
</template>
