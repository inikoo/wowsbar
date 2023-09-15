<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faImage, faExpandArrows, faAlignCenter, faTrash, faStopwatch } from "../../../private/pro-light-svg-icons"
import PrimitiveInput from '@/Components/Forms/Fields/Primitive/PrimitiveInput.vue'
import Select from '@/Components/Forms/Fields/Primitive/PrimitiveSelect.vue'
import Radio from '@/Components/Forms/Fields/Primitive/PrimitiveRadio.vue'
import SlideBackground from "@/Components/Workshop/Fields/SlideBackground.vue"
import Corners from "@/Components/Workshop/Fields/Corners.vue"
import Colorpicker from '@/Components/Workshop/Fields/ColorPicker.vue'
import SelectFont from '@/Components/Workshop/Fields/SelectFont.vue'
import TextAlign from '@/Components/Workshop/Fields/TextAlign.vue'

const components = {
    'text': PrimitiveInput,
    'radio': Radio,
    'slideBackground': SlideBackground,
    'corners': Corners,
    'colorpicker' : Colorpicker,
    'select': Select,
    'selectFont': SelectFont,
    'textAlign': TextAlign
}

const getComponent = (componentName: string) => {
    return components[componentName]
}

const dummyData = [
    {
        "name": [
            "layout",
            "centralStage",
            "title"
        ],
        "type": "text",
        "label": "Title",
        "value": [
            "layout",
            "centralStage",
            "title"
        ]
    },
    {
        "name": [
            "layout",
            "centralStage",
            "subtitle"
        ],
        "type": "text",
        "label": "subtitle",
        "value": [
            "layout",
            "centralStage",
            "subtitle"
        ]
    },
    {
        "name": [
            "layout",
            "centralStage",
            "style",
            "fontFamily"
        ],
        "type": "selectFont",
        "label": "Font Family",
        "value": [
            "layout",
            "centralStage",
            "style",
            "fontFamily"
        ],
        "options": [
            "Arial",
            "Comfortaa",
            "Lobster",
            "Laila",
            "Port Lligat Slab",
            "Playfair",
            "Quicksand",
            "Times New Roman",
            "Yatra One"
        ]
    },
    {
        "name": [
            "layout",
            "centralStage",
            "textAlign"
        ],
        "type": "textAlign",
        "label": "Text Align",
        "value": [
            "layout",
            "centralStage",
            "textAlign"
        ],
        "options": ["left", "center", "right"]
    },
    {
        "name": [
            "layout",
            "centralStage",
            "style",
            "fontSize"
        ],
        "type": "radio",
        "label": "Font Size",
        "value": [
            "layout",
            "centralStage",
            "style",
            "fontSize"
        ],
        "defaultValue": {
            "fontTitle": "text-[25px] lg:text-[44px]",
            "fontSubtitle": "text-[12px] lg:text-[20px]"
        },
        "options": [
            {
                "label": "Extra Small",
                "value": {
                    "fontTitle": "text-[13px] lg:text-[21px]",
                    "fontSubtitle": "text-[8px] lg:text-[12px]"
                }
            },
            {
                "label": "Small",
                "value": {
                    "fontTitle": "text-[18px] lg:text-[32px]",
                    "fontSubtitle": "text-[10px] lg:text-[15px]"
                }
            },
            {
                "label": "Normal",
                "value": {
                    "fontTitle": "text-[25px] lg:text-[44px]",
                    "fontSubtitle": "text-[12px] lg:text-[20px]"
                }
            },
            {
                "label": "Large",
                "value": {
                    "fontTitle": "text-[30px] lg:text-[60px]",
                    "fontSubtitle": "text-[15px] lg:text-[25px]"
                }
            },
            {
                "label": "Extra Large",
                "value": {
                    "fontTitle": "text-[40px] lg:text-[70px]",
                    "fontSubtitle": "text-[20px] lg:text-[30px]"
                }
            }
        ]
    },
    {
        "name": [
            "layout",
            "centralStage",
            "style",
            "color"
        ],
        "type": "colorpicker",
        "label": "color",
        "value": [
            "layout",
            "centralStage",
            "style",
            "color"
        ]
    }
]


</script>

<template>
    <div>
        <div class="bg-indigo-500 w-full py-10 flex items-center justify-center">
            <div class="text-gray-200 text-5xl">
                dddddddddddddd
            </div>
        </div>

        <div class="px-4 sm:px-6 md:px-4 pt-6 xl:pt-4 col-span-9 flex flex-grow justify-center overflow-auto">
            <div class="flex flex-col w-full ">
                <dl v-for="(fieldData, index) in dummyData" :key="index"
                    class="pb-4 sm:pb-5 sm:gap-4 w-full">
                    <!-- Title -->
                    <dt v-if="fieldData.label"
                        class="text-sm font-medium text-gray-500 capitalize">
                        <div class="inline-flex items-start leading-none">
                            <span>{{ fieldData.label }}</span>
                        </div>
                    </dt>

                    <!-- Fields -->
                    <dd class="flex text-sm text-gray-700 sm:mt-0 w-full">
                        <div class="relative flex-grow">
                            <component :is="getComponent(fieldData['type'])" :data="currentComponentBeenEdited"
                                :fieldName="fieldData.name" :fieldData="fieldData" :key="index" :counter="false"
                                :common="common">
                            </component>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</template>

<style scoped></style>