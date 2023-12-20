<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:57:22 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref , defineExpose } from "vue";
import GrapeEditor from "@/Components/CMS/Workshops/GrapeEditor/MJML.vue";
import Unlayer from "@/Components/CMS/Workshops/GrapeEditor/Unlayer.vue";
import LabelEstimated from "@/Components/Mailshots/LabelEstimated.vue";
import { trans } from "laravel-vue-i18n";
import { useLocaleStore } from "@/Stores/locale";
import Button from "../Elements/Buttons/Button.vue";
import { faThLarge, faEdit } from "@fas/";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Modal from "@/Components/Utils/Modal.vue";
import TemplateMailshot from "@/Components/CMS/TemplateMailshot/TemplateMailshot.vue";
import axios from "axios";
import { notify } from "@kyvg/vue3-notification";
import { faSpinnerThird } from '@fad'

library.add(faThLarge, faEdit, faSpinnerThird);
const props = defineProps<{
    title: string;
    updateRoute: object;
    loadRoute: object;
    imagesUploadRoute: Object;
    mailshot: object;
    updateDetailRoute: object;
    changeTitle?:Function
}>();
const editSubject = ref(false);
const editor = import.meta.env.VITE_MAILSHOT_EDITOR;
const openTemplates = ref(false);
const editorRef = ref(null);
const subject = ref(props.title)
const emits = defineEmits();
const loadingState = ref(false)

const getComponent = (componentName: string) => {
    const components: any = {
        grape: GrapeEditor,
        unlayer: Unlayer,
    };
    return components[componentName] ?? null;
};

const changeTemplate = (template) => {
    if (editorRef.value.setToNewTemplate)
        editorRef.value.setToNewTemplate(
            JSON.parse(JSON.stringify(template.html.design))
        );
    openTemplates.value = false;
};

const StoreTemplate = async (template) => {
    try {
        const response = await axios.post(
            route(props.updateRoute.name, props.updateRoute.parameters),
            { data: ["first template"], pagesHtml: template.html }
        );
        props.mailshot.is_layout_blank = false;
    } catch (error) {
        console.log(error);
        notify({
            title: "Failed",
            text: "failed to get template",
            type: "error",
        });
    }
};


const onEditSubject = async () => {
    loadingState.value = true
    try {
        const response = await axios.post(
            route(props.updateDetailRoute.name, props.updateDetailRoute.parameters),
            { subject: subject.value, _method: "patch" }
        );
        editSubject.value = false;
        props.changeTitle(subject.value)
        loadingState.value = false
    } catch (error) {
        console.log(error);
        editSubject.value = false;
        loadingState.value = false
        notify({
            title: "Failed",
            text: "failed to update Subject",
            type: "error",
        });
    }
}

defineExpose({
    editor : editorRef, 
})


</script>

<template>
    <LabelEstimated
        :emailsEstimated="mailshot.stats.number_estimated_dispatched_emails"
        :idMailshot="mailshot.id"
    >
        <template #content>
            <div class="flex w-full">
                <div class="text-gray-500 w-1/2">
                    {{ trans("Estimated recipients") }}:
                    <span class="font-semibold text-gray-700">{{
                        useLocaleStore().number(
                            mailshot.stats.number_estimated_dispatched_emails
                        )
                    }}</span>
                </div>
                <div
                    v-if="!mailshot.is_layout_blank"
                    class="text-gray-500 w-1/2 flex justify-end"
                >
                    <Button
                        icon="fas fa-th-large"
                        label="Template"
                        :style="'tertiary'"
                        size="xs"
                        @click="openTemplates = true"
                    />
                </div>
            </div>
        </template>
    </LabelEstimated>
    <LabelEstimated
        :emailsEstimated="mailshot.stats.number_estimated_dispatched_emails"
        :idMailshot="mailshot.id"
    >    
        <template #content>
            <div class="flex w-full">
                <div class="text-gray-500 w-1/2">
                    {{ trans("Subject") }}:
                    <span
                        v-if="!editSubject"
                        @click="editSubject = true"
                        class="font-semibold text-gray-700 cursor-pointer"
                        >{{ title }}  <font-awesome-icon :icon="['fas', 'edit']" class="text-gray-300 text-sm ml-2 cursor-pointer" /></span
                    >
                    <span v-else
                        ><input
                            type="text"
                            @input="(e)=> subject = e.target.value"
                            :value="subject"
                            @blur="onEditSubject"
                            @keyup.enter="onEditSubject"
                            class="appearance-none bg-transparent border-none focus:outline-none focus:border-none p-0"
                    /> <FontAwesomeIcon v-if="loadingState" icon='fad fa-spinner-third' aria-hidden="true" /></span>
                </div>
            </div>
        </template>
    </LabelEstimated>

    <component
        v-if="!mailshot.is_layout_blank"
        :is="getComponent(editor)"
        @onSaveToServer="
            (isDirtyFromServer) => (isDataDirty = isDirtyFromServer)
        "
        :useBasic="true"
        :plugins="[]"
        :updateRoute="updateRoute"
        :loadRoute="loadRoute"
        :imagesUploadRoute="imagesUploadRoute"
        ref="editorRef"
    >
    </component>

    <div v-else class="p-5">
        <TemplateMailshot @changeTemplate="StoreTemplate" :mailshot="mailshot"/>
    </div>

    <Modal :isOpen="openTemplates" @onClose="openTemplates = false">
        <div class="overflow-y-auto">
            <TemplateMailshot @changeTemplate="changeTemplate" :mailshot="mailshot"/>
        </div>
    </Modal>
</template>


<style scoped>
[type='text']:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    --tw-ring-inset: var(--tw-empty,/*!*/ /*!*/);
    --tw-ring-offset-width: 0px;
    --tw-ring-offset-color: #fff;
    --tw-ring-color: transparent;
    --tw-ring-offset-shadow:transparent;
    --tw-ring-shadow: transparent;
    border-color: transparent;
}

</style>