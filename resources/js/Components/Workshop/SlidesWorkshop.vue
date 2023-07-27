<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script  setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faTrashAlt, faAlignJustify } from "@/../private/pro-light-svg-icons"
import { faEye, faEyeSlash } from "@/../private/pro-solid-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import draggable from "vuedraggable"
import { ulid } from 'ulid'
import Input from '@/Components/Forms/Fields/Input.vue'
import { trans } from "laravel-vue-i18n"
import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue"
import Button from '../Elements/Buttons/Button.vue'
import { get } from 'lodash'
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
const fileInput = ref(null)
const currentComponentBeenEdited = ref(props.data.components[0])
const commonEditActive = ref(false)
const addComponent = () => {
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
                    visibility : true
                }
            })
        }
    }
    const newFiles = [...setData]
    props.data.components = [... props.data.components, ...newFiles]
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

const removeComponent = (file) => {
    const index =  props.data.components.findIndex(item => item.ulid === file.ulid);
    if (index !== -1) {
        console.log(currentComponentBeenEdited.value);
        if (currentComponentBeenEdited.value && currentComponentBeenEdited.value.ulid ===  props.data.components[index].ulid) {
            const nextIndex = index + 1;
            selectComponentForEdition(nextIndex <  props.data.components.length ?  props.data.components[nextIndex] :  props.data.components.filter((item)=>item.ulid !== null)[0])
        }
        props.data.components[index].ulid = null;
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
                    visibility : true
                }
            })
        }
    }
    const newFiles = [...setData]
    props.data.components = [...props.data.components, ...newFiles]
    isDragging.value = false
}

const selectComponentForEdition = (slide) => {
    commonEditActive.value = false
    currentComponentBeenEdited.value = slide
    _SlideWorkshop.value.current = 0
}

const visible = (file) => {
    const index = props.data.components.findIndex((item) => item.ulid === file.ulid);
    if (index !== -1) {
        props.data.components[index].layout.visibility = !props.data.components[index].layout.visibility;
    }
};

const ComponentsBlueprint = ref([
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
        icon: ['fal', 'fa-trash'],
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


const CommonBlueprint = ref([
    {
        title: 'Duration',
        icon: ['fal', 'fa-image'],
        fields: [
            {
                name: 'delay',
                type: 'range',
                label: trans('Duration'),
                value: null
            },
        ]

    },
    // {
    //     title: 'Common',
    //     icon: ['fal', 'fa-expand-arrows'],
    //     fields: [
    //         {
    //             name: 'delay',
    //             type: 'input',
    //             label: null,
    //             value: null
    //         },
    //     ]

    // },
    // {
    //     title: 'Button Position',
    //     icon: ['fal', 'fa-align-center'],
    //     fields: [
    //         {
    //             name: ['layout', 'centralStage', 'title'],
    //             type: 'input',
    //             label: trans('Title'),
    //             value: ['layout', 'centralStage', 'title']
    //         },
    
    //     ]
    // },
])


const _SlideWorkshop = ref(null)

const setCommonEdit =()=>{
    commonEditActive.value = !commonEditActive.value 
    if(commonEditActive.value) currentComponentBeenEdited.value = null
    else currentComponentBeenEdited.value = props.data.components[0]
}

</script>

<template>
    <div class="flex flex-grow gap-2.5">
        <div class="w-[30%] lg:w-2/3 p-2.5 border rounded h-fit shadow" v-if="data.components"
            @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div :class="['border-gray-300 p-2 border mb-2 text-center', { 'border-orange-500': commonEditActive }]" @click="setCommonEdit">
                {{ trans('Common properties') }}
            </div>

            <!-- Drag area -->
            <div class="mb-2 text-lg font-medium">{{ trans('Slides') }}</div>
            <draggable :list="data.components" group="slide " item-key="ulid"
                handle=".handle" >
                <template #item="{ element: file }">
                    <div
                        v-if="file.ulid !== null"
                        :class="[file.ulid != get(currentComponentBeenEdited,'ulid') ?
                            'border-gray-300' :
                            'border-l-orange-500 border-l-4 bg-gray-200/60',
                        'grid grid-flow-col relative py-1 border mb-2 items-center justify-between hover:cursor-pointer']"
                        @click="selectComponentForEdition(file)"
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
                                @click="visible(file)" title="Show/hide the slide">
                                <FontAwesomeIcon v-if="file.layout.visibility" icon="fas fa-eye" class="text-xs sm:text-sm text-gray-400 hover:text-gray-500" />
                                <FontAwesomeIcon v-else icon="fas fa-eye-slash" class="text-xs sm:text-sm text-gray-300 hover:text-gray-400/70" />
                            </button>
                        </div>
                    </div>
                </template>
            </draggable>

            <!-- Button: Add slide -->
            <!-- Remove the input element from inside the label -->
            <label
                class="relative inline-block"
                id="input-slide-large-mask" for="fileInput"
            >
                <input ref="fileInput" type="file" multiple name="file" id="fileInput"
                    @change="addComponent" accept="image/*"
                    class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
                <Button :style="`secondary`" icon="fas fa-plus" size="xs">{{ trans("Add slide") }}</Button>
            </label>
        </div>

        <!-- The Editor -->
        <div class="w-full border border-gray-300" v-if="currentComponentBeenEdited != null">
            <SlideWorkshop :currentComponentBeenEdited="currentComponentBeenEdited" :blueprint="ComponentsBlueprint" ref="_SlideWorkshop" :remove="removeComponent"></SlideWorkshop>
        </div>

        <div class="w-full border border-gray-300" v-if="commonEditActive">
            <SlideWorkshop :currentComponentBeenEdited="props.data" :blueprint="CommonBlueprint" ref="_SlideWorkshop" :remove="removeComponent"></SlideWorkshop>
        </div>
    </div>
</template>
