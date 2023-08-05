<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import Hero from '@/Components/Marketing/Hero.vue';
import {Hero as heroType} from "@/types/hero";


const props = defineProps<{
    hero: heroType
}>()
</script>

<template layout="Landlord">
    <Head title="Homepage"/>
    <Hero :hero="hero"></Hero>
</template>
