<script setup lang="ts">
import { onMounted, ref } from "vue";
import { routeType } from "@/types/route"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
import { cloneDeep } from "lodash";
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

defineOptions({ layout: PreviewWorkshop })
const props = defineProps<{
    footer: Object
    autosaveRoute: routeType
}>()

const socketLayout = SocketFooter();
const ToolWorkshop = ref({
    previewMode: false,
    usedTemplates: cloneDeep(props.footer.data)
})

onMounted(() => {
    if (socketLayout) socketLayout.actions.subscribe((value) => {
        ToolWorkshop.value = {
                ...ToolWorkshop.value,
                usedTemplates: value.footer.data
            }
    });
    const channel = window.Echo.join(`footer.preview`)
        .listenForWhisper("otherIsNavigating", (event: any) => {
            ToolWorkshop.value = {
                ...ToolWorkshop.value,
                previewMode: event.data.previewMode,
            }
        })
});

</script>

<template>
    <div class="p-4">
        <Footer1 v-model="ToolWorkshop.usedTemplates.data.footer" :preview-mode="ToolWorkshop.previewMode" />
    </div>
</template>


<style scss></style>
