<script setup lang="ts">
import { onMounted, ref, onUnmounted, reactive } from "vue";
import { routeType } from "@/types/route"
import { footerTheme1 } from '@/Components/Workshop/Footer/descriptor'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification"



import { faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload } from '@far';
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faLineColumns, faIcons, faMoneyBill, faUpload, faDownload)

defineOptions({ layout: PreviewWorkshop })
const props = defineProps<{
    footer: Object
    autosaveRoute: routeType
}>()

const saveCancelToken = ref<Function | null>(null)
const socketLayout = SocketFooter();
const usedTemplates = reactive(footerTheme1)
const debouncedSendUpdate = debounce((data) => autoSave(data), 5000, { leading: false, trailing: true })
const previewMode = ref(route().params['fullscreen'] ? true : false)

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

const updateData = (newVal) => {
    debouncedSendUpdate({...usedTemplates , data : {fieldValue : newVal }  });
}

onMounted(() => {
    if (socketLayout) socketLayout.actions.subscribe((value) => {
        usedTemplates.data = value.footer.data
    });
    window.addEventListener('message', (event) => {
        if (event.data.key === 'previewMode') {
            previewMode.value = event.data.value
        }
    });
});

onUnmounted(() => {
    if (socketLayout) socketLayout.actions.unsubscribe();
});


</script>

<template>
    <div class="p-4">
        <Footer1 
            v-model="usedTemplates.data.fieldValue" 
            :preview-mode="previewMode"
            @update:model-value="updateData"
        />
    </div>
</template>


<style scoped>
</style>
