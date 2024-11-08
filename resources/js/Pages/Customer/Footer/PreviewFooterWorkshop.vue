<script setup lang="ts">
import { onMounted, ref, onUnmounted, reactive, toRaw, watch } from "vue";
import { routeType } from "@/types/route"
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification"
// import { getComponent } from "@/Composables/Website/getComponentsFooters"
import { trans } from 'laravel-vue-i18n'

import { computed, type Component } from 'vue'
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'

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
const usedTemplates = reactive(props.footer.data)
const debouncedSendUpdate = debounce((data) => autoSave(data), 5000, { leading: false, trailing: true })
const previewMode = ref(route().params['fullscreen'] ? true : false)

// watch(() => props.footer?.data?.code, () => {
//     usedTemplates = props.footer.data
// })

const autoSave = async (data: Object) => {
    console.log(data)
    sendMessageToParent('autosave', data)
    /* router.patch(
        route(props.autosaveRoute.name, props.autosaveRoute.parameters),
        { layout: data },
        {
            onFinish: () => {
                saveCancelToken.value = null
                sendMessageToParent('autosave',usedTemplates)
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
    ) */
}

const updateData = (newVal) => {
    autoSave({ ...usedTemplates, data: { fieldValue: newVal } })
   /*  debouncedSendUpdate({ ...usedTemplates, data: { fieldValue: newVal } }); */
}

const isWorkshop = ref(false)
onMounted(() => {
    /* if (socketLayout) socketLayout.actions.subscribe((value) => {
        Object.assign(usedTemplates, value.footer.data);
    }); */

    sendMessageToParent('isFooterInWorkshop', true)

    window.addEventListener('message', (event) => {
        if (event.data.key === 'previewMode') {
            previewMode.value = event.data.value
        }

        // If the parent tell that this preview Footer in the workshop
        if (event.data.key === 'isWorkshop') {
            isWorkshop.value = event.data.value
        }

        if (event.data.key === 'reload') {
            console.log('reload')
            router.reload({
                only: ['footer'],
                onSuccess: () => {
                    Object.assign(usedTemplates, toRaw(props.footer.data));
                }
            });
        }
    });
});

onUnmounted(() => {
    if (socketLayout) socketLayout.actions.unsubscribe();
});

const sendMessageToParent = (key: string, value: any) => {
    // Ensure the data is JSON-serializable
    const serializableValue = JSON.parse(JSON.stringify(value));
    window.parent.postMessage({ key, value: serializableValue }, '*');
};




const getComponent = computed(() => {
    const components: Component = {
        'FooterTheme1': Footer1,
        'FooterTheme2': Footer1,
        'FooterTheme3': Footer1,
    }

    return components[usedTemplates.code] || null
})



</script>

<template>
    <div class="p-4">
        <component
            v-if="usedTemplates?.code"
            :is="getComponent"
            v-model="usedTemplates.data.fieldValue"
            :previewMode
            :isWorkshop
            @update:model-value="updateData"
        />
    </div>
</template>


<style scoped></style>
