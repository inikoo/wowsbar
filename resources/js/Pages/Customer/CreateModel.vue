<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import Create from "@/Components/Create.vue";
import { library } from "@fortawesome/fontawesome-svg-core"

import { faToggleOn,faEdit,faUserLock,faBell,faCopyright, faUserCircle, faKey, faClone, faPaintBrush, faMoonStars, faLightbulbOn, faCheck, faPhone, faIdCard, faFingerprint,faLanguage,faAddressBook,faTrashAlt } from "@/../private/pro-light-svg-icons"
import { faGoogle} from "@fortawesome/free-brands-svg-icons"

library.add(faToggleOn,faEdit,faUserLock,faBell,faCopyright,faUserCircle, faKey, faClone, faPaintBrush, faMoonStars, faLightbulbOn, faCheck, faPhone, faIdCard, faFingerprint,faLanguage,faAddressBook,faTrashAlt, faGoogle)


const props = defineProps<{
    title: string,
    pageHead: object,
    formData: {
        blueprint: object;
        route: {
            name: string,
            arguments?: Array<string>
        };
    }
}>()


</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Create :formData="formData" appName="customer"/>
</template>

