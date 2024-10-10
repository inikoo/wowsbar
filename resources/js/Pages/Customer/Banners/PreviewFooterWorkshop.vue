<script setup lang="ts">
import { onMounted } from "vue";
import { routeType } from "@/types/route"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

const props = defineProps<{
    data: {
        footer: Object
    }
    autosaveRoute: routeType
}>()
defineOptions({ layout: PreviewWorkshop })

onMounted(() => {
    const channel = window.Echo.join(`footer.preview`)
                        .listenForWhisper("otherIsNavigating", (event : any ) => { console.log(event) })
    console.log('preview',channel)
});


</script>

<template>
    <div class="p-4">
        <Footer1 
            v-model="footerTheme1.data.footer" 
            :preview-mode="false" 
        />
    </div>
</template>


<style scss></style>
