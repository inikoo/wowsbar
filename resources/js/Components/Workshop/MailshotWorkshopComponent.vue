<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:57:22 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from "vue"
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/MJML.vue'
import Unlayer from '@/Components/CMS/Workshops/GrapeEditor/Unlayer.vue'
import { cloneDeep } from 'lodash'
import LabelEstimated from '@/Components/Mailshots/LabelEstimated.vue'
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'
import Button from "../Elements/Buttons/Button.vue"
import { faThLarge } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import Modal from '@/Components/Utils/Modal.vue'
import TemplateMailshot from '@/Components/CMS/TemplateMailshot/TemplateMailshot.vue'
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"


library.add(faThLarge)
const props = defineProps<{
    title: string
    updateRoute: object,
    loadRoute: object
    imagesUploadRoute: Object
    mailshot: object
}>()

const editor = import.meta.env.VITE_MAILSHOT_EDITOR
const openTemplates = ref(false)
const editorRef = ref(null)


const getComponent = (componentName: string) => {
    const components: any = {
        'grape': GrapeEditor,
        'unlayer': Unlayer
    };
    return components[componentName] ?? null;

};

const changeTemplate = (template) => {
    console.log(template)
    if (editorRef.value.setToNewTemplate) editorRef.value.setToNewTemplate(JSON.parse(JSON.stringify(template.compiled.html.design)))
    openTemplates.value = false
}

const StoreTemplate = async (template) => {
    try {
        const response = await axios.post(
            route(
                props.updateRoute.name,
                props.updateRoute.parameters
            ),
            { data: ['first template'] , pagesHtml: template.compiled.html },
        )
        props.mailshot.is_layout_blank = false
    } catch (error) {
        console.log(error)
        notify({
        title: "Failed",
        text: 'failed to get template',
        type: "error"
    });
    }
}



</script>
  
<template>
    <LabelEstimated :emailsEstimated="mailshot.stats.number_estimated_dispatched_emails">
        <template #content>
            <div class="flex w-full">
                <div class="text-gray-500 w-1/2">
                    {{ trans('Estimated recipients') }}:
                    <span class="font-semibold text-gray-700">{{
                        useLocaleStore().number(mailshot.stats.number_estimated_dispatched_emails) }}</span>
                </div>
                <div v-if="!mailshot.is_layout_blank"  class="text-gray-500 w-1/2 flex justify-end">
                    <Button icon="fas fa-th-large" label="Template" :style="'tertiary'" size="xs"
                        @click="openTemplates = true" />
                </div>
            </div>
        </template>
    </LabelEstimated>

    <component v-if="!mailshot.is_layout_blank" :is="getComponent(editor)"
        @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer" :useBasic="true" :plugins="[]"
        :updateRoute="updateRoute" :loadRoute="loadRoute" :imagesUploadRoute="imagesUploadRoute" ref="editorRef">
    </component>

    <div v-else class="p-5">
        <TemplateMailshot @changeTemplate="StoreTemplate" />
    </div>

    <Modal :isOpen="openTemplates" @onClose="openTemplates = false">
        <div class="overflow-y-auto">
            <TemplateMailshot @changeTemplate="changeTemplate" />
        </div>
    </Modal>
</template>
  
  



