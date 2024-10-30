<script setup lang="ts">
import { onMounted, ref, watch, onUnmounted, reactive } from "vue";
import { routeType } from "@/types/route"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { debounce, isEqual } from 'lodash'
import { router } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification"


import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
import { cloneDeep } from "lodash";
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

defineOptions({ layout: PreviewWorkshop })
const props = defineProps<{
    footer: Object
    autosaveRoute: routeType
}>()

const saveCancelToken = ref<Function | null>(null)
const socketLayout = SocketFooter();
const usedTemplates = reactive({ data : props.footer.data ?  {...props.footer.data} : footerTheme1 })
const debouncedSendUpdate = debounce((data) => autoSave(data), 5000, { leading: false, trailing: true })
const ToolWorkshop = ref({
    previewMode: false,
})


const autoSave = async (data: Object) => {
    router.patch(
        route("customer.models.banner.workshop.footers.autosave.footer"),
        { layout: data },
        {
            onFinish: () => {
                saveCancelToken.value = null
            },
            onCancelToken: (cancelToken) => {
                saveCancelToken.value = cancelToken.cancel
            },
            onCancel: () => {
                console.log('The saving progress canceled.')
            },
            onError: (error) => {
                notify({
                    title: trans('Something went wrong.'),
                    text: error.message,
                    type: 'error',
                })
            },
            preserveScroll: true,
            preserveState: true,
        }
    )
}

/* watch(usedTemplates.data.data.footer, 
  (newVal) => {
    if (saveCancelToken.value) {
        saveCancelToken.value()
    }
    console.log('fgfgfg',saveCancelToken.value)
      debouncedSendUpdate({...usedTemplates.data, data : {footer : newVal, bluprint : usedTemplates.data.data.bluprint}  });
  },
  { deep: true }
); */

const updateData = (newVal) => {
    debouncedSendUpdate({...usedTemplates.data, data : {footer : newVal, bluprint : usedTemplates.data.data.bluprint}  });
}


onMounted(() => {
    if (socketLayout) socketLayout.actions.subscribe((value) => {
        usedTemplates.data = value.footer.data
    });
    const channel = window.Echo.join(`footer.preview`)
        .listenForWhisper("otherIsNavigating", (event: any) => {
            ToolWorkshop.value = {
                ...ToolWorkshop.value,
                previewMode: event.data.previewMode,
            }
        })
});


onUnmounted(() => {
    if (socketLayout) socketLayout.actions.unsubscribe();
});

</script>

<template>
    <div class="p-4">
        <Footer1 
            v-model="usedTemplates.data.data.footer" 
            :preview-mode="ToolWorkshop.previewMode"
            @update:model-value="updateData"
        />
    </div>
</template>


<style scoped>
</style>
