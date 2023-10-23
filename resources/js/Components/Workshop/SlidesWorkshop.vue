<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watch, } from "vue"
import { useBannerBackgroundColor } from "@/Composables/useColorList"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import {
    faTrashAlt,
    faAlignJustify,
    faCog,
    faImage,
    faLock,
} from '@fal/'
// import {  } from '@far/'
import { faEye, faEyeSlash } from '@fas/'
import { library } from "@fortawesome/fontawesome-svg-core"
import draggable from "vuedraggable"
import { ulid } from "ulid"
import Input from "@/Components/Forms/Fields/Input.vue"
import { trans } from "laravel-vue-i18n"
import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue"
import Button from "../Elements/Buttons/Button.vue"
import { get, isNull } from "lodash"
import SliderCommonWorkshop from "./SliderCommonWorkshop.vue"
import Modal from '@/Components/Utils/Modal.vue'
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import CropImage from "@/Components/Workshop/CropImage/CropImage.vue"
import Image from "@/Components/Image.vue"

library.add(faEye, faEyeSlash, faTrashAlt, faAlignJustify, faCog, faImage, faLock)

interface CornersPositionData {
    data: {
        text: string;
        target: string;
    };
    type: string;
}

interface Corners {
    topLeft?: CornersPositionData;
    topRight?: CornersPositionData;
    bottomLeft?: CornersPositionData;
    bottomRight?: CornersPositionData;
}

const props = defineProps<{
    data: {
        common: {
            centralStage: {
                subtitle?: string;
                text?: string;
                title?: string;
            };
            corners: Corners;
        };
        components: Array<{
            id: number;
            image_id: number;
            image_source: string;
            ulid?: number;
            layout: {
                link?: string;
                centralStage: {
                    title?: string;
                    subtitle?: string;
                    // text?: string,
                    // footer?: string
                };
            };
            visibility: boolean;
            corners: Corners;
            imageAlt: string;
            link: string;
        }>;
        delay: number;
        type: string
    };
    imagesUploadRoute: {
        name: string;
        arguments: string[]
    };
    user: string
    screenView: string
}>();

const emits = defineEmits<{
    (e: "jumpToIndex", id: number): void;
}>();

const isDragging = ref(false);
const fileInput = ref(null);
const currentComponentBeenEdited = ref();
const commonEditActive = ref(true);
const isOpenGalleryImages = ref(false);
const _SlideWorkshop = ref(null);
const uploadedFilesList = ref([]);

const isOpenCropModal = ref(false);

const closeCropModal = () => {
    uploadedFilesList.value = [];
    isOpenCropModal.value = false;
    fileInput.value.value = "";
};

const addComponent = async (element) => {
    uploadedFilesList.value = element.target.files;
    isOpenCropModal.value = true;
};

const removeComponent = (file) => {
    const index = props.data.components.findIndex((item) => item.ulid === file.ulid);
    if (index !== -1) {
        props.data.components.splice(index,1)
    }
};

const dragover = (e) => {
    e.preventDefault();
    isDragging.value = true;
};

const dragleave = () => {
    isDragging.value = false;
};

const drop = (e) => {
    e.preventDefault();
    uploadedFilesList.value = e.dataTransfer.files;
    if( e.dataTransfer.files.length > 0 ) isOpenCropModal.value = true;
    isDragging.value = false;
};

const selectComponentForEdition = (slide) => {
    const componentToEdit = props.data.components.find((item) => item.ulid === slide.ulid);

    if (!componentToEdit) {
        console.log("Component not found");
        return;
    }

    if (slide.user && slide.user !== props.user) {
        console.log("Cannot edit: Component already in use");
    } else {
        const oldComponentToEdit = props.data.components.find(
            (item) => item.ulid === get(currentComponentBeenEdited.value, "ulid")
        );

        if (oldComponentToEdit) {
            oldComponentToEdit.user = null;
        }

        componentToEdit.user = props.user;
        if (commonEditActive) {
            props.data.common.user = null;
        }
        commonEditActive.value = false;
        currentComponentBeenEdited.value = slide;
        if (!isNull(_SlideWorkshop.value)) _SlideWorkshop.value.current = 0;
    }
};

watch(
    currentComponentBeenEdited,
    (value, oldValue) => {
        if (value !== null) {
            const component = [...props.data.components]; // Create a shallow copy of the components array
            const index = component.findIndex((item) => item.ulid === value.ulid);
            if (index !== -1) {
                component[index] = { ...value };
                props.data.components = component;
            }
        }
    },
    { deep: true }
);

// To change visibility of the each slide
const changeVisibility = (slide: any) => {
    const index = props.data.components.findIndex((item) => item.ulid === slide.ulid);
    if (index !== -1) {
        props.data.components[index].hasOwnProperty("visibility")
            ? (props.data.components[index].visibility = !props.data.components[index].visibility)
            : (props.data.components[index].visibility = false);
    }
};

const ComponentsBlueprint = ref([
    {
        title: "Background & Link",
        icon: ["fal", "fa-image"],
        fields: [
            {
                name: "image",
                type: "slideBackground",
                label: trans("Image"),
                value: ["image"],
                uploadRoute: props.imagesUploadRoute,
            },
            {
                name: ["layout", "link"],
                type: "text",
                label: trans("Link"),
                value: ["layout", "link"],
                defaultValue : 'https://',
                rules:{
                    pattern : '^(http|https)://',
                    message : 'please input https:// or http://'
                }
            },
        ],
    },
    {
        title: "corners",
        icon: ["fal", "fa-expand-arrows"],
        fields: [
            {
                name: ["layout", "corners"],
                type: "corners",
                label: null,
                value: null,
                optionType: ["cornerText", "linkButton", "ribbon", 'clear'],
            },
        ],
    },
    {
        title: "central stage",
        icon: ["fal", "fa-align-center"],
        fields: [
            {
                name: ["layout", "centralStage", "title"],
                type: "text",
                label: trans("Title"),
                value: ["layout", "centralStage", "title"],
                placeholder: "Holiday Sales!"
            },
            {
                name: ["layout", "centralStage", "subtitle"],
                type: "text",
                label: trans("subtitle"),
                value: ["layout", "centralStage", "subtitle"],
                placeholder: "Holiday sales up to 80% all items."
            },
            {
                name: ["layout", "centralStage", "style", "fontFamily"],
                type: "selectFont",
                label: trans("Font Family"),
                value: ["layout", "centralStage", "style", "fontFamily"],
                options: [
                    "Arial",
                    "Comfortaa",
                    "Lobster",
                    "Laila",
                    "Port Lligat Slab",
                    "Playfair",
                    "Quicksand",
                    "Times New Roman",
                    "Yatra One",
                ],
            },
            {
                name: ["layout", "centralStage", "textAlign"],
                type: "textAlign",
                label: trans("Text Align"),
                value: ["layout", "centralStage", "textAlign"],
                options: [
                    {
                        label: "Align left",
                        value: "left",
                        icon: 'fal fa-align-left'
                    },
                    {
                        label: "Align center",
                        value: "center",
                        icon: 'fal fa-align-center'
                    },
                    {
                        label: "Align right",
                        value: "right",
                        icon: 'fal fa-align-right'
                    },
                ],
            },
            {
                name: ["layout", "centralStage", "style", "fontSize"],
                type: "radio",
                label: trans("Font Size"),
                value: ["layout", "centralStage", "style", "fontSize"],
                defaultValue: { fontTitle: "text-[25px] lg:text-[44px]", fontSubtitle: "text-[12px] lg:text-[20px]" },
                options: [
                    { label: "Extra Small", value: {
                            fontTitle: "text-[13px] lg:text-[21px]",
                            fontSubtitle: "text-[8px] lg:text-[12px]"
                        }
                    },
                    {
                        label: "Small",
                        value: {
                            fontTitle: "text-[18px] lg:text-[32px]",
                            fontSubtitle: "text-[10px] lg:text-[15px]"
                        }
                    },
                    {
                        label: "Normal",
                        value: {
                            fontTitle: "text-[25px] lg:text-[44px]",
                            fontSubtitle: "text-[12px] lg:text-[20px]"
                        }
                    },
                    {
                        label: "Large", value: {
                            fontTitle: "text-[30px] lg:text-[60px]",
                            fontSubtitle: "text-[15px] lg:text-[25px]"
                        }
                    },
                    {
                        label: "Extra Large",
                        value: {
                            fontTitle: "text-[40px] lg:text-[70px]",
                            fontSubtitle: "text-[20px] lg:text-[30px]"
                        },
                    },
                ],
            },
            {
                name: ["layout", "centralStage", "style", "color"],
                type: "colorpicker",
                label: trans("Text Color"),
                icon: 'far fa-text',
                value: ["layout", "centralStage", "style", "color"],
            },
            // {
            //     name: ["layout", "centralStage", "style", "textShadow"],
            //     type: "toogle",
            //     label: trans("Text Shadow"),
            //     value: ["layout", "centralStage", "style", "TextShadow"],
            // },
        ],
    },
    // {
    //     title: "delete",
    //     icon: ["fal", "fa-trash-alt"],
    //     fields: [
    //         {
    //             name: ["layout", "centralStage", "title"],
    //             type: "delete",
    //             label: trans("Title"),
    //             value: ["layout", "centralStage", "title"],
    //         },
    //     ],
    // },
]);

const CommonBlueprint = ref([
    {
        title: "Duration",
        icon: ["fal", "stopwatch"],
        fields: [
            {
                name: "delay",
                type: "range",
                label: trans("Duration"),
                value: null,
                timeRange: {
                    min: "2.5",
                    max: "15",
                    step: "0.5",
                    range: ["2.5", "5", "7.5", "10", "12.5", "15"],
                },
            },
        ],
    },
    {
        title: "corners",
        icon: ["fal", "fa-expand-arrows"],
        fields: [
            {
                name: ["common", "corners"],
                type: "corners",
                label: null,
                value: null,
                optionType: ["cornerText", "linkButton", "slideControls", "ribbon", 'clear'],
            },
        ],
    },
    {
        title: "central stage",
        icon: ["fal", "fa-align-center"],
        fields: [
            {
                name: ["common", "centralStage", "title"],
                type: "text",
                label: trans("Title"),
                value: ["common", "centralStage", "title"],
                placeholder: "Enter title of the slide"
            },
            {
                name: ["common", "centralStage", "subtitle"],
                type: "text",
                label: trans("subtitle"),
                value: ["common", "centralStage", "subtitle"],
                placeholder: "Enter subtitle of the slide"
            },
            {
                name: ["common", "centralStage", "style", "fontFamily"],
                type: "selectFont",
                label: trans("Font Family"),
                value: ["common", "centralStage", "style", "fontFamily"],
            },
            {
                name: ["common", "centralStage", "textAlign"],
                type: "textAlign",
                label: trans("Text Align"),
                value: ["common", "centralStage", "textAlign"],
                options: [
                    {
                        label: "Align left",
                        value: "left",
                        icon: 'fal fa-align-left'
                    },
                    {
                        label: "Align center",
                        value: "center",
                        icon: 'fal fa-align-center'
                    },
                    {
                        label: "Align right",
                        value: "right",
                        icon: 'fal fa-align-right'
                    },
                ],
            },
            {
                name: ["common", "centralStage", "style", "fontSize"],
                type: "radio",
                label: trans("Font Size"),
                value: ["common", "centralStage", "style", "fontSize"],
                defaultValue: { fontTitle: "text-[25px] lg:text-[44px]", fontSubtitle: "text-[12px] lg:text-[20px]" },
                options: [
                    { label: "Extra Small", value: {
                            fontTitle: "text-[13px] lg:text-[21px]",
                            fontSubtitle: "text-[8px] lg:text-[12px]"
                        }
                    },
                    {
                        label: "Small",
                        value: {
                            fontTitle: "text-[18px] lg:text-[32px]",
                            fontSubtitle: "text-[10px] lg:text-[15px]"
                        }
                    },
                    {
                        label: "Normal",
                        value: {
                            fontTitle: "text-[25px] lg:text-[44px]",
                            fontSubtitle: "text-[12px] lg:text-[20px]"
                        }
                    },
                    {
                        label: "Large", value: {
                            fontTitle: "text-[30px] lg:text-[60px]",
                            fontSubtitle: "text-[15px] lg:text-[25px]"
                        }
                    },
                    {
                        label: "Extra Large",
                        value: {
                            fontTitle: "text-[40px] lg:text-[70px]",
                            fontSubtitle: "text-[20px] lg:text-[30px]"
                        },
                    },
                ],
            },
            {
                name: ["common", "centralStage", "style", "color"],
                type: "colorpicker",
                label: trans("color"),
                value: ["common", "centralStage", "style", "color"],
            },
            // {
            //     name: ["layout", "centralStage", "style", "textShadow"],
            //     type: "toogle",
            //     label: trans("Text Shadow"),
            //     value: ["layout", "centralStage", "style", "TextShadow"],
            // },
            // {
            //     name: ["common", "centralStage", "style", "textShadow"],
            //     type: "TextShadow",
            //     label: trans("Text Shadow"),
            //     value: ["common", "centralStage", "style", "textShadow"],
            // },
        ],
    },
]);


const setCommonEdit = () => {
    if (props.data.common.user && props.data.common.user !== props.user) {
        console.log("masih dipakek");
    } else {
        const oldComponentToEdit = props.data.components.find(
            (item) => item.ulid === get(currentComponentBeenEdited.value, "ulid")
        );
        props.data.common.user = props.user;
        if (oldComponentToEdit) {
            oldComponentToEdit.user = null;
        }

        commonEditActive.value = !commonEditActive.value;

        if (commonEditActive.value) {
            currentComponentBeenEdited.value = null;
        } else {
            currentComponentBeenEdited.value = props.data.components[0];
        }
    }
};

const uploadImageRespone = (res) => {
    let setData = [];
    for (const set of res.data) {
        setData.push({
            id:  null,
            ulid: ulid(),
            layout: {
                imageAlt: set.name,
            },
            image: {
                desktop: set,
            },
            backgroundType: {
                desktop: 'image'
            },
            visibility: true,
        });
    }
    const newFiles = [...setData];
    props.data.components = [...props.data.components, ...newFiles];
    isOpenCropModal.value = false;
    isOpenGalleryImages.value = false
    commonEditActive.value = false
    currentComponentBeenEdited.value = props.data.components[ props.data.components.length - 1 ]
};

// Onclick button 'Add Slide'
const addNewSlide = () => {
    let setData = []
    setData.push({
        id:  null,
        ulid: ulid(),
        layout: {
            imageAlt: 'New slide',
        },
        image: {
            desktop: {},
            tablet: {},
            mobile: {},
        },
        background: {
            desktop: backgroundColorList[Math.floor(Math.random() * backgroundColorList.length)], // To random the background color on new slide
            tablet: backgroundColorList[Math.floor(Math.random() * backgroundColorList.length)],
            mobile: backgroundColorList[Math.floor(Math.random() * backgroundColorList.length)],
        },
        backgroundType: {
            desktop: 'color'
        },
        visibility: true,
    });
    const newFiles = [...setData];
    props.data.components = [...props.data.components, ...newFiles];
}

const backgroundColorList = useBannerBackgroundColor() // Fetch color list from Composables

</script>

<template>
    <!-- <pre>{{ data.components }}</pre> -->
    <div class="flex flex-grow gap-2.5">
        <div class="p-2.5 border rounded h-fit shadow w-1/4"
            v-if="data.components" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <!-- Common Properties -->
            <div :class="[
                'p-2 mb-4 md:pl-3 cursor-pointer space-x-3 md:space-x-2 ring-1 ring-gray-300 flex flex-row items-center md:block',
                commonEditActive
                    ? 'navigationActiveCustomer bg-gray-200/60 font-medium'
                    : 'navigationCustomer hover:bg-gray-100 border-gray-300',
                ]" @click="setCommonEdit"
            >
                <FontAwesomeIcon v-if="props.data.common.user == props.user || !props.data.common.user"
                    icon="fal fa-cog" class="text-xl md:text-base text-gray-500" aria-hidden="true" />
                <FontAwesomeIcon v-else="
                    props.data.common.user == props.user || !props.data.common.user
                    " :icon="['fal', 'lock']" class="" aria-hidden="true" />
                <span class="text-gray-600 text-sm hidden sm:inline">{{ trans("Common properties") }}</span>
            </div>

            <!-- Slides/Drag area -->
            <div class="text-lg font-medium leading-none">{{ trans("Slides") }}</div>
            <draggable :list="data.components" group="slide " item-key="ulid" handle=".handle" class="max-h-96 overflow-auto p-0.5"
                :onChange="(e: any) => emits('jumpToIndex', e.moved.newIndex)">
                <template #item="{ element: slide }">
                    <div @mousedown="
                            selectComponentForEdition(slide),
                            emits(
                                'jumpToIndex',
                                data.components.findIndex(obj => obj.ulid === slide.ulid)
                            )"
                        v-if="slide.ulid" :class="[
                            'grid grid-flow-col relative sm:py-1 mb-2 items-center justify-between ring-1 ring-gray-300',
                            slide.ulid == get(currentComponentBeenEdited, 'ulid')
                                ? 'navigationSecondActiveCustomer font-medium'
                                : 'navigationSecondCustomer',
                            slide.user != props.user && slide.user
                                ? 'border-l-gray-500 border-l-4 bg-gray-200/60 text-gray-600 font-medium cursor-not-allowed'
                                : 'cursor-pointer',
                        ]"
                    >
                        <!-- Slide -->
                        <div class="grid grid-flow-col gap-x-1 lg:gap-x-0 ssm:py-1 lg:py-0">
                            <!-- Icon: Bars, class 'handle' to grabable -->
                            <FontAwesomeIcon icon="fal fa-bars"
                                class="handle p-1 text-xs sm:text-base sm:p-2.5 text-gray-700 cursor-grab place-self-center" />
                            
                            <!-- Image slide: if Image is selected in SlideBackground -->
                            <div v-if="get(slide, ['backgroundType', screenView ? screenView : 'desktop'], 'image') === 'image'">
                                <Image :src="get(slide, ['image', screenView ? screenView : 'desktop', 'thumbnail'], null)" class="h-full w-10 sm:w-10 flex items-center justify-center py-1"/>
                            </div>
                            
                            <div v-else>
                                <!-- If the slide is color -->
                                <!-- {{ slide.backgroundType.desktop }} -->
                                <div :style="{ background: get(slide, ['background', screenView ? screenView : 'desktop'], 'gray')}" class="h-full w-10 sm:w-10 flex items-center justify-center py-1"/>
                            </div>

                            <!-- Label slide -->
                            <div class="hidden lg:inline-flex overflow-hidden whitespace-nowrap overflow-ellipsis pl-2 leading-tight flex-auto items-center">
                                <div class="overflow-hidden whitespace-nowrap overflow-ellipsis lg:text-xs xl:text-sm">
                                    {{ slide?.layout?.imageAlt ?? "Image " + slide.id }}
                                </div>
                            </div>
                        </div>

                        <!-- Button: Show/hide, delete slide -->
                        <div class="flex justify-center items-center pr-2 justify-self-end"  v-if="slide.user == props.user || !slide.user">
                            <button class="px-2 py-1 bg-grays-500 text-red-500/60 hover:text-red-500" type="button" v-if="!slide.visibility"
                                @click="(e)=>{ e.stopPropagation()
                                    removeComponent(slide)}"
                                title="Delete the slide">
                                <FontAwesomeIcon :icon="['fal', 'fa-trash-alt']"  class="text-xs sm:text-sm" />
                            </button>
                            <button class="px-2 py-1 text-gray-400 hover:text-gray-500" type="button"
                                @click="changeVisibility(slide)" title="Show/hide the slide">
                                <FontAwesomeIcon v-if="slide.hasOwnProperty('visibility') ? slide.visibility : true"
                                    icon="fas fa-eye" class="text-xs sm:text-sm " />
                                <FontAwesomeIcon v-else icon="fas fa-eye-slash" class="text-xs sm:text-sm" />
                            </button>
                        </div>

                        <div v-else class="flex justify-center items-center pr-2 justify-self-end">
                            <div class="px-2 py-1" type="button" title="Edited by other user">
                                <FontAwesomeIcon :icon="['fal', 'lock']" class="text-xs sm:text-sm text-gray-600" />
                            </div>
                        </div>

                    </div>
                </template>
            </draggable>

            <!-- Button: Add slide, Gallery -->
            <div class="flex flex-wrap md:flex-row gap-x-2 gap-y-1 lg:gap-y-0 w-full justify-between">
                <Button @click="isOpenGalleryImages = !isOpen" :style="`tertiary`" icon="fal fa-photo-video" label="Gallery" size="xs" class="relative w-full flex justify-center lg:w-fit lg:inline space-x-2" id="gallery" />

                <!-- <Button :style="`secondary`" size="xs" class="relative w-full flex justify-center lg:w-fit lg:inline space-x-2">
                    <FontAwesomeIcon icon='fas fa-plus' class='' aria-hidden='true' />
                    <span>{{ trans("Add slide") }}</span>
                    <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                        for="fileInput" />
                    <input ref="fileInput" type="file" multiple name="file" id="fileInput" @change="addComponent"
                        accept="image/*" class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
                </Button> -->

                 <Button :style="`secondary`" size="xs" @click="addNewSlide" class="relative w-full flex justify-center lg:w-fit lg:inline space-x-2">
                    <FontAwesomeIcon icon='fas fa-plus' class='' aria-hidden='true' />
                    <span>{{ trans("Add slide") }}</span>
                </Button>

                
            </div>
            <div class="text-xs text-gray-400 py-1">
                The recommended image size is 1800 x 450
            </div>
        </div>

        <!-- The Editor: Common Properties -->
        <div class="border border-gray-300 w-3/4 rounded-md" v-if="commonEditActive">
            <SliderCommonWorkshop ref="_SlideWorkshop" :currentComponentBeenEdited="props.data" :blueprint="CommonBlueprint"
                />
        </div>

        <!-- The Editor: Slide -->
        <div class="border border-gray-300 w-3/4 rounded-md" v-if="currentComponentBeenEdited != null">
            <SlideWorkshop ref="_SlideWorkshop" :bannerType="data.type" :common="data.common" :currentComponentBeenEdited="currentComponentBeenEdited"
                :blueprint="ComponentsBlueprint" :remove="removeComponent" />
        </div>

        <!-- Modal: Gallery -->
        <Modal :isOpen="isOpenGalleryImages" @onClose="isOpenGalleryImages = false">
            <div>
                <GalleryImages
                 :addImage="uploadImageRespone" 
                 :closeModal="()=>isOpenGalleryImages = false"  
                 :imagesUploadRoute="props.imagesUploadRoute"  
                 :ratio="data.type == 'square' ? {w: 1, h: 1} : {w: 4, h: 1}" 
                 />
            </div>
        </Modal>

        <!-- Modal: Crop (add slide) -->
        <Modal :isOpen="isOpenCropModal" @onClose="closeCropModal">
            <div>
                <CropImage
                    :ratio="data.type == 'square' ? {w: 1, h: 1} : {w: 4, h: 1}"
                    :data="uploadedFilesList"
                    :imagesUploadRoute="props.imagesUploadRoute"
                    :response="uploadImageRespone" />
            </div>
        </Modal>
    </div>
</template>
