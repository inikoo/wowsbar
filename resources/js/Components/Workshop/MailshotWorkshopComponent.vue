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


library.add( faThLarge )
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

const isDataDirty = ref(cloneDeep(props.isDirty))

const getComponent = (componentName: string) => {
    const components: any = {
        'grape': GrapeEditor,
        'unlayer': Unlayer
    };
    return components[componentName] ?? null;

};

const changeTemplate=(template)=>{
    console.log('inii',editorRef.value)
    if(editorRef.value.setToNewTemplate) editorRef.value.setToNewTemplate(JSON.parse(JSON.stringify(template.compiled.html.design)))
    openTemplates.value = false
}

console.log('inii',editorRef)

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
                <div class="text-gray-500 w-1/2 flex justify-end">
                    <Button icon="fas fa-th-large" label="Template" :style="'tertiary'" size="xs" @click="openTemplates = true"/>
                </div>
            </div>
        </template>
    </LabelEstimated>
    <component :is="getComponent(editor)" @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer"
        :useBasic="true" :plugins="[]" :updateRoute="updateRoute" :loadRoute="loadRoute"
        :imagesUploadRoute="imagesUploadRoute" ref="editorRef">
    </component>


    <Modal :isOpen="openTemplates" @onClose="openTemplates = false">
        <div class="overflow-y-auto">
            <TemplateMailshot @changeTemplate="changeTemplate"/>
        </div>
    </Modal>

</template>
  
  



