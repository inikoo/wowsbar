<script setup lang="ts">
import { routeType } from "@/types/route"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'

import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

const props = defineProps<{
    data: {
        footer: Object
    }
    autosaveRoute: routeType
}>()

</script>

<template>
<Footer1  v-model="footerTheme1.data.footer" :preview-mode="false"/>


</template>


<style scss></style>
