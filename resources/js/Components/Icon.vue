<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 30 Aug 2023 19:08:20 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {capitalize} from '@/Composables/capitalize.js';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    data: {
        tooltip?: string,
        icon: object,
        class?:string

    },
}>()
</script>

<template>
    <FontAwesomeIcon
         :title="capitalize(data.tooltip ?? '')"
         aria-hidden="true"
         :icon="data.icon"
         size="sm"
         :class="data.class"
    />
</template>
