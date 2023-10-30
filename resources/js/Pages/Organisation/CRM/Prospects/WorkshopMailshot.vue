<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';

import {capitalize} from "@/Composables/capitalize"

import {faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup} from '@fal/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";

library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    workshop?: object,
}>()

console.log(props)



</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <MailshotWorkshopComponent/>
</template>

