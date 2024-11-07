<script setup lang="ts">
import { onMounted, ref, onUnmounted, reactive } from "vue";
import { routeType } from "@/types/route"
import PreviewWorkshop from "@/Layouts/BlankLayout.vue";
import { SocketFooter } from "@/Composables/SocketWebBlock"
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification"
import { getComponent } from "@/Composables/Website/getComponentsFooters"
import { trans } from 'laravel-vue-i18n'

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

const autoSave = async (data: Object) => {
    sendMessageToParent('autosave', usedTemplates)
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
    debouncedSendUpdate({ ...usedTemplates, data: { fieldValue: newVal } });
}

onMounted(() => {
    /* if (socketLayout) socketLayout.actions.subscribe((value) => {
        Object.assign(usedTemplates, value.footer.data);
    }); */

        window.addEventListener('message', (event) => {
            if (event.data.key === 'previewMode') {
                previewMode.value = event.data.value
            }
            if (event.data.key === 'reload') {
                router.reload({
                    only: ['footer'],
                    onSuccess: () => {
                        Object.assign(usedTemplates, props.footer.data);
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



</script>

<template>
    <div class="p-4">
        <component v-if="usedTemplates?.code" :is="getComponent(usedTemplates.code)"
            v-model="usedTemplates.data.fieldValue" :preview-mode="previewMode" @update:model-value="updateData" />
    </div>
</template>


<style scoped></style>
