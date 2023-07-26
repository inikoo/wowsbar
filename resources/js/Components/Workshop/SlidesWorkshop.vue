<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script  setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faTrashAlt, faAlignJustify } from "@/../private/pro-light-svg-icons"
import { faEye, faEyeSlash } from "@/../private/pro-solid-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core';
import draggable from "vuedraggable"
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ulid } from 'ulid';
import Input from '@/Components/Forms/Fields/Input.vue'
import { trans } from "laravel-vue-i18n"
import { useForm } from '@inertiajs/vue3'
import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue";
import { cloneDeep } from 'lodash'
import Button from '../Elements/Buttons/Button.vue';
library.add(faEye, faEyeSlash, faTrashAlt, faAlignJustify)

interface CornersPositionData {
    data: {
        text: string
        target: string
    }
    type: string
}

interface Corners {
    topLeft?: CornersPositionData
    topRight?: CornersPositionData
    bottomLeft?: CornersPositionData
    bottomRight?: CornersPositionData
}

const props = defineProps<{
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: Array<
            {
                id: number,
                image_id: number
                image_source: string
                layout: {
                    link?: string,
                    centralStage: {
                        title?: string
                        subtitle?: string
                        // text?: string,
                        // footer?: string
                    }
                }
                corners: Corners
                imageAlt: string
                link: string
                visibility: boolean
            }
        >
        delay: number

    }

}>()

const isDragging = ref(false)
const components = ref(props.data.components)
const fileInput = ref(null)
const fileEdit = ref(components.value[0])
const onChange = () => {
    let setData = []
    for (const set of fileInput.value?.files) {
        if (set && set instanceof File) {
            setData.push({
                id: null,
                image_id: ulid(),
                image_source: null,
                imageFile: set,
                ulid: ulid(),
                layout: {
                    imageAlt: set.name,
                }
            })
        }
    }
    const newFiles = [...setData]
    components.value = [...components.value, ...newFiles]
    props.data.components = [...components.value]
}

const generateThumbnail = (file) => {
    if (file.imageFile && file.imageFile instanceof File) {
        let fileSrc = URL.createObjectURL(file.imageFile)
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc)
        }, 1000)
        return fileSrc
    } else {
        return file.image_source
    }
}

const remove = (file) => {
    const index = components.value.findIndex(item => item.ulid === file.ulid);
    if (index !== -1) {
        console.log(fileEdit.value);
        if (fileEdit.value && fileEdit.value.ulid === components.value[index].ulid) {
            const nextIndex = index + 1;
            openEdit(nextIndex < components.value.length ? components.value[nextIndex] : components.value.filter((item)=>item.ulid !== null)[0])
        } 
        components.value[index].ulid = null;
        props.data.components = components.value; // Remove items with null ulid
    } else {
        console.log('Index not found');
    }
};


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
                id: null,
                image_id: ulid(),
                image_source: null,
                imageFile: set,
                ulid: ulid(),
                layout: {
                    imageAlt: set.name,
                }
            })
        }
    }
    const newFiles = [...setData]
    components.value = [...components.value, ...newFiles]
    isDragging.value = false
}

const openEdit = (file) => {
    fileEdit.value = file
    _SlideWorkshop.value.current = 0
    setFormValue(file)
}

const visible = (i) => {
    components.value[i].layout.visibility = !components.value[i].layout.visibility
    props.data.components = [...components.value]
}

const blueprint = ref([
    {
        title: 'Background',
        icon: ['fal', 'fa-image'],
        fields: [
            {
                name: 'image_source',
                type: 'slideBackground',
                label: trans('Image'),
                value: ['image_source']
            },
        ]

    },
    {
        title: 'corners',
        icon: ['fal', 'fa-expand-arrows'],
        fields: [
            {
                name: 'top-left',
                type: 'corners',
                label: null,
                value: null
            },
        ]

    },
    {
        title: 'central stage',
        icon: ['fal', 'fa-align-center'],
        fields: [
            {
                name: ['layout', 'centralStage', 'title'],
                type: 'input',
                label: trans('Title'),
                value: ['layout', 'centralStage', 'title']
            },
            {
                name: ['layout', 'centralStage', 'subtitle'],
                type: 'input',
                label: trans('subtitle'),
                value: ['layout', 'centralStage', 'subtitle']
            },
        ]
    },
    {
        title: 'delete',
        icon: ['fas', 'fa-trash'],
        fields: [
            {
                name: ['layout', 'centralStage', 'title'],
                type: 'delete',
                label: trans('Title'),
                value: ['layout', 'centralStage', 'title']
            },
        ]
    },
])


onMounted(() => {
    setFormValue(fileEdit.value)
});

const _SlideWorkshop = ref(null)
const form = ref({});
const setFormValue = (data) => { form.value = useForm(data) }

const applyChanges = () => {
    if (form.value.data()) {
        let oldFile = cloneDeep(fileEdit.value),
            newFile = cloneDeep(form.value.data())
        fileEdit.value = { ...newFile, layout: { ...newFile.layout, visibility: oldFile.layout.visibility } };
        const index = components.value.findIndex((item) => item.ulid === fileEdit.value.ulid);
        if (index !== -1) components.value[index] = fileEdit.value;
        props.data.components = components.value;
    }
}

</script>

<template>
    <div class="flex flex-grow gap-2.5">
        <div class="w-[30%] lg:w-2/3 p-2.5 border-dashed" style="border: 1px dashed #d9d9d9;" v-if="data.components"
            @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div class='border-gray-300 p-2 border mb-2 text-center'>
                {{ trans('Common properties') }}
            </div>

            <!-- Drag area -->
            <div class="mb-2 text-lg font-medium">{{ trans('Slides') }}</div>
            <draggable :list="data.components.filter((item) => item.ulid !== null)" group="slide " item-key="ulid"
                handle=".handle">
                <template #item="{ element: file }">
                    <div 
                        v-if="file.ulid !== null"
                        :class="[file.ulid != fileEdit.ulid ?
                            'border-gray-300' :
                            'border-l-orange-500 border-l-4 bg-gray-200/60',
                        'grid grid-flow-col relative py-1 border mb-2 items-center justify-between hover:cursor-pointer']"
                        @click="openEdit(file)"
                    >
                        <div class="grid grid-flow-col gap-x-1 py-1">
                            <!-- Icon: Bars, class 'handle' to grabable -->
                            <FontAwesomeIcon icon="fal fa-bars" class="handle p-1 text-xs sm:text-base sm:p-2.5 text-gray-700 cursor-grab place-self-center" />
                            
                            <!-- Image slide -->
                            <div class="h-5 w-5 sm:h-10 sm:w-10 bg-contain flex items-center justify-center">
                                <img class="h-5 sm:h-10 max-w-full shadow" :src="generateThumbnail(file)" />
                            </div>

                            <!-- Label slide -->
                            <div class="hidden lg:inline-flex overflow-hidden whitespace-nowrap overflow-ellipsis pl-2 leading-tight flex-auto items-center">
                                <div class="overflow-hidden whitespace-nowrap overflow-ellipsis lg:text-xs xl:text-sm">{{ file.layout.imageAlt }}</div>
                            </div>
                        </div>

                        <!-- Button: Show/hide, delete slide -->
                        <div class="flex justify-center items-center pr-2 justify-self-end">
                            <button class="px-2 py-1" type="button"
                                @click="visible(components.indexOf(file))" title="Show/hide the slide">
                                <FontAwesomeIcon v-if="file.layout.visibility" icon="fas fa-eye" class="text-xs sm:text-sm text-gray-400 hover:text-gray-500" />
                                <FontAwesomeIcon v-else icon="fas fa-eye-slash" class="text-xs sm:text-sm text-gray-300 hover:text-gray-400/70" />
                            </button>
                        </div>
                    </div>
                </template>
            </draggable>

            <!-- Button: Add slide -->
            <PrimaryButton class="m-2.5">
                <!-- Remove the input element from inside the label -->
                <label for="fileInput" class="flex items-center">
                    <input type="file" multiple name="file" id="fileInput"
                        class="opacity-0 overflow-hidden absolute w-1 h-1" @change="onChange" ref="fileInput"
                        accept="image/*" />
                    <FontAwesomeIcon :icon="['fas', 'plus']" class="mr-1" /> Banner
                </label>
            </PrimaryButton>
        </div>

        <!-- The Editor -->
        <div class="w-full border border-gray-300">
            <SlideWorkshop :fileEdit="fileEdit" :blueprint="blueprint" :form="form" ref="_SlideWorkshop" :remove="remove"></SlideWorkshop>
            <div class="border border-gray-200 flex justify-end  p-1" style="height: 10%;">
                <Button @click=applyChanges>
                    Apply
                </Button>
            </div>
        </div>
    </div>
</template>
