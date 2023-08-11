<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 16:55:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { useLayoutStore } from "@/Stores/layout";
import AppTopBarNavItems from "@/Layouts/TopBar/AppTopBarNavItems.vue";
const layout = useLayoutStore();

</script>

<template>
    <div class="flex">
        <AppTopBarNavItems />
    </div>
</template>

