<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, onMounted} from 'vue'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faAlignJustify} from "../../../private/pro-solid-svg-icons"
import {faTrashAlt,faEye} from "../../../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
import draggable from "vuedraggable"
import {get} from 'lodash'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {v4 as uuidv4} from 'uuid';
import Input from '@/Components/Forms/Fields/Input.vue'
import Select from '@/Components/Forms/Fields/Select.vue'

import {trans} from "laravel-vue-i18n"
import Radio from '@/Components/Forms/Fields/Radio.vue'

import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import {useForm} from '@inertiajs/vue3'
import SlideBackground from "@/Components/Workshop/Fileds/SlideBackground.vue";
import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue";

library.add(faTrashAlt,faAlignJustify,faEye)
const props = defineProps<{
    files: any
    filesChange: Function
    changeLink: Function
}>()

const isDragging = ref(false)
const files = ref(props.files)
const fileInput = ref(null)
const open = ref(false)
const fileEdit = ref(files.value[0])

const onChange = () => {
    let setData = []
    for (const set of fileInput.value.files) {
        if (set && set instanceof File) {
            setData.push({
                file: set,
                link: {label: "open", target: ""},
                imageAlt: set.name,
                imageSrc: 'img',
                id: uuidv4()
            })
        }
    }
    const newFiles = [...setData]
    files.value = [...files.value, ...newFiles]
    props.filesChange([...files.value])
}

const generateThumbnail = (file) => {
    if (file.file && file.file instanceof File) {
        let fileSrc = URL.createObjectURL(file.file)
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc)
        }, 1000)
        return fileSrc
    } else {
        return getImageUrl(file.imageSrc)
    }
}

const getImageUrl = (name: string) => {
    return new URL(`@/../../../../../../art/banner/` + name, import.meta.url).href
}

const generateName = (file) => {
    return file.imageAlt
}

const remove = (i) => {
    files.value.splice(i, 1)
    props.filesChange([...files.value])
}

const dragover = (e) => {
    e.preventDefault()
    isDragging.value = true
}

const dragleave = () => {
    isDragging.value = false
}

const drop = (e) => {
    e.preventDefault()
    let setData = []
    for (const set of e.dataTransfer.files) {
        if (set && set instanceof File) {
            setData.push({
                file: set,
                link: {label: "open", target: ""},
                imageAlt: set.name,
                imageSrc: 'img',
                id: uuidv4()
            })
        }
    }
    const newFiles = [...setData]
    files.value = [...files.value, ...newFiles]
    isDragging.value = false
}

const openEdit = (file) => {
    fileEdit.value = file
    open.value = true
}

const openCommonEdit = () => {console.log('Open common properties form')}

const changeIndex = () => {
    props.filesChange(files.value)
}

const getComponent = (componentName) => {
    const components = {
        'input': Input,
        'inputWithAddOn': InputWithAddOn,
        'select': Select,
        'radio': Radio,
        'slideBackground': SlideBackground
    };
    return components[componentName]
};


const blueprint = ref([
    {
        title: trans('Background'),
        icon: ['fal', 'fa-image'],
        fields: [
            {
                name: 'file',
                type: 'slideBackground',
                label: trans('image'),
                value: get(fileEdit.value, 'imageSrc')
            },
        ]

    },
    {
        title: trans('corners'),
        icon: ['fal', 'fa-expand-arrows'],
        fields: [
            {
                name: 'top-left',
                type: 'slideBackground',
                label: trans('top left corner'),
                value: get(fileEdit.value, 'imageSrc')
            },
        ]

    },
    {
        title: trans('central stage'),
        icon: ['fal', 'fa-align-center'],
        fields: [
            {
                name: 'title',
                type: 'input',
                label: trans('Title'),
                value: get(fileEdit.value, 'imageSrc')
            },
            {
                name: 'sub-title',
                type: 'input',
                label: trans('subtitle'),
                value: get(fileEdit.value, 'imageSrc')
            },
        ]

    },




])
const current = ref(0)
let fields = {};
onMounted(() => {
    setFormValue()
});

const setFormValue = () => {
    for (const data of blueprint.value) {
        Object.entries(data.fields).forEach(([fieldName, fieldData]) => {
            fields[fieldName] = fieldData['value'];
        });
    }
}


const form = useForm(fields);

</script>

<template>
    <div class="main">
        <div class="container-preview" v-if="files.length" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div class="mb-2 text-lg font-medium">{{ trans('Common') }}</div>
            <div class="flex relative h-66 p-2 border border-gray-300 rounded-md mb-2 items-center">

                <div class="title cursor-pointer " @click="openCommonEdit">
                    <div class="overflow-hidden whitespace-nowrap overflow-ellipsis">{{ trans('Common properties') }}</div>
                </div>

            </div>
            <div class="mb-2 text-lg font-medium">{{ trans('Slides') }}</div>
            <draggable :list="files" group="files" item-key="id" handle=".handle" @change="changeIndex">
                <template #item="{ element: file }">
                    <div
                        :class="file.id !== fileEdit.id ? 'flex relative h-66 p-2 border border-gray-300 rounded-md mb-2 items-center' : 'bg-orange-50 border-orange-500 text-orange-700 flex relative h-66 p-2 border rounded-md mb-2 items-center'">
                        <font-awesome-icon icon="fa fa-align-justify"
                                           class="handle p-2.5 text-gray-300 cursor-grab"></font-awesome-icon>
                        <div class="img">
                            <img class="preview-img" :src="generateThumbnail(file)"/>
                        </div>
                        <div class="title cursor-pointer " @click="openEdit(file)">
                            <div class="overflow-hidden whitespace-nowrap overflow-ellipsis">{{ generateName(file) }}</div>
                        </div>
                        <div class="flex justify-center items-center m-2.5">

                            <button class="ml-2" type="button" @click="remove(files.indexOf(file))" :title="trans('Hide')">
                                <font-awesome-icon :icon="['fal', 'eye']"/>
                            </button>
                            <button class="ml-2 text-rose-500" type="button" @click="remove(files.indexOf(file))" :title="trans('Remove')">
                                <font-awesome-icon :icon="['fal', 'trash-alt']"/>
                            </button>


                        </div>
                    </div>
                </template>
            </draggable>

            <PrimaryButton class="mt-2.5">
                <input type="file" multiple name="file" id="fileInput" class="hidden-input" @change="onChange" ref="fileInput"
                       accept=".pdf,.jpg,.jpeg,.png"/>
                <label for="fileInput">
                    <font-awesome-icon :icon="['fas', 'plus']" class="mr-1"/>
                    {{ trans('Slide') }}</label>
            </PrimaryButton>
        </div>


        <div style="width: 70%; border: 1px solid #d9d9d9;">

            <SlideWorkshop :data="{}"   note="use a computed property to inject the correct selected slide data to this component" />

        </div>


    </div>
</template>

<style>
.main {
    display: flex;
    flex-grow: 1;
    gap: 10px;
}

.dropzone-container {
    height: 208px;
    width: 100%;
    text-align: center;
    background: rgba(0, 0, 0, 0.02);
    border: 1px dashed #d9d9d9;
    border-radius: 8px;
    cursor: pointer;
    transition: border-color 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hidden-input {
    opacity: 0;
    overflow: hidden;
    position: absolute;
    width: 1px;
    height: 1px;
}

.preview-img {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 48px;
    height: 48px;
    line-height: 60px;
    text-align: center;
    flex: none;
}

.title {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 0 8px;
    line-height: 1.5714285714285714;
    flex: auto;
    transition: all 0.3s;
}

.container-preview {
    width: 30%;
    padding: 10px;
    border: 1px dashed #d9d9d9;
}
</style>
