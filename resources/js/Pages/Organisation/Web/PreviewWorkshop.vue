<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import {
    faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite
} from "@/../private/pro-light-svg-icons"

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { computed, defineAsyncComponent, ref } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { capitalize } from "@/Composables/capitalize"
import FooterWorkshop from "@/Components/CMS/Workshops/FooterWorkshop.vue";
import MenuWorkshop from "@/Components/CMS/Workshops/MenuWorkshop.vue";
import HeaderWorkshop from "@/Components/CMS/Workshops/HeaderWorkshop.vue";

library.add(
    faArrowAltToTop,
    faArrowAltToBottom,
    faBars,
    faBrowser,
    faCube,
    faPalette,
    faCookieBite
)



</script>


<template layout="OrgApp">
    <!--suppress HtmlRequiredTitleElement -->
    <!-- <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading> -->
    preview
</template>

