<script setup lang='ts'>
import Moveable from "vue3-moveable"
import { propertiesToHTMLStyle, onDrag } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { computed, ref } from "vue"
import { closeIcon } from '@/Composables/useAnnouncement'
import { AnnouncementData } from "@/types/Announcement"
library.add(faTimes)

const props = defineProps<{
    announcementData?: AnnouncementData
    _parentComponent?: Element
    isEditable?: boolean
    isToSelectOnly?: boolean
}>()

const emits = defineEmits<{
    (e: 'templateClicked',  template: AnnouncementData): void
}>()

const _text_1 = ref(null)
const _buttonClose = ref(null)

const fieldSideEditor = [
    {
        name: "Container",
        icon: {
            icon: "fal fa-rectangle-wide",
            tooltip: "Container"
        },
        replaceForm: [
            {
                type: "properties"
            }
        ]
    },
]

const defaultContainerData = {
    "link": {
        "href": "#",
        "target": "_blank"
    },
    "border": {
        "top": {
            "value": 0
        },
        "left": {
            "value": 0
        },
        "unit": "px",
        "color": "rgba(243, 243, 243, 1)",
        "right": {
            "value": 0
        },
        "bottom": {
            "value": 0
        },
        "rounded": {
            "unit": "px",
            "topleft": {
                "value": 0
            },
            "topright": {
                "value": 0
            },
            "bottomleft": {
                "value": 0
            },
            "bottomright": {
                "value": 0
            }
        }
    },
    "margin": {
        "top": {
            "value": 0
        },
        "left": {
            "value": 0
        },
        "unit": "px",
        "right": {
            "value": 0
        },
        "bottom": {
            "value": 0
        }
    },
    "padding": {
        "top": {
            "value": 20
        },
        "left": {
            "value": 20
        },
        "unit": "px",
        "right": {
            "value": 20
        },
        "bottom": {
            "value": 20
        }
    },
    "position": {
        "x": "0%",
        "y": "20px",
        "type": "fixed"
    },
    "dimension": {
        "width": {
            "unit": "%",
            "value": 100
        },
        "height": {
            "unit": "px",
            "value": "50"
        }
    },
    "background": {
        "type": "color",
        "color": "linear-gradient(90deg, rgba(221,245,254,1) 0%, rgba(252,247,255,1) 16%, rgba(255,252,246,1) 35%, rgba(248,240,255,1) 57%, rgba(255,250,246,1) 83%)",
        "image": {
            "original": null
        }
    },
    "text": {
        "color": "rgba(10,10,10,1)",
        "fontFamily": "Raleway"
    },
    "isCenterHorizontal": false
}

const defaultFieldsData = {
    "text_1": {
        "text": "<p>Pure Ingredients, Pure Health: <strong>20% Off</strong> on Organic Goods!</p>",
        "block_properties": {
            "position": {
                "x": "50%",
                "y": "50%",
                "type": "absolute"
            }
        }
    },
    "text_2": {
        "text": "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet nisi at elit venenatis fringilla. Cras ut semper quam, sit.</p>",
        "block_properties": {
            "position": {
                "x": "20%",
                "y": "30%",
                "type": "absolute"
            }
        }
    },
    "button_1": {
        "link": {
            "type" : "internal",
            "url": "https://ancientwisdom.biz/showroom",
            "id": 9,
            "workshop_route" : ""
        },
        "text": '<span style="font-size: 10px"><strong>Claim Now!</strong></span>',
        "container": {
            "properties": {
                "text": {
                    "color": "rgba(255, 255, 255, 1)",
                    "fontFamily": null
                },
                "background": {
                    "type": "color",
                    "color": "rgba(20,20,20,1)",
                    "image": {
                        "original": null
                    }
                },
                "padding": {
                    "unit": "px",
                    "top": {
                        "value": 3
                    },
                    "left": {
                        "value": 15
                    },
                    "right": {
                        "value": 15
                    },
                    "bottom": {
                        "value": 3
                    }
                },
                "margin": {
                    "unit": "px",
                    "top": {
                        "value": 0
                    },
                    "left": {
                        "value": 0
                    },
                    "right": {
                        "value": 0
                    },
                    "bottom": {
                        "value": 0
                    }
                },
                "border": {
                    "color": "#000000",
                    "unit": "px",
                    "rounded": {
                        "unit": "px",
                        "topright": {
                            "value": 4
                        },
                        "topleft": {
                            "value": 4
                        },
                        "bottomright": {
                            "value": 4
                        },
                        "bottomleft": {
                            "value": 4
                        }
                    },
                    "top": {
                        "value": 0
                    },
                    "left": {
                        "value": 0
                    },
                    "right": {
                        "value": 0
                    },
                    "bottom": {
                        "value": 0
                    }
                }
            }
        }
    },
    "close_button": {
        "size": "0.5",
        "block_properties": {
            "text": {
                "color": "rgba(0, 0, 0, 0.5)",
            },
            "position": {
                "x": "97%",
                "y": "50%",
                "type": "absolute"
            }
        }
    }
}

const componentDefaultData = {
    code: 'announcement-promo-1',
    fields: defaultFieldsData,
    container_properties: defaultContainerData
}

const onClickClose = () => {
    window.parent.postMessage('close_button_click', '*');
}

const camelToKebab = (str: string) => {
    return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
};

const styleString = (styleObject: {}) => {
    if (!styleObject) return ''

    return Object.entries(styleObject)
    .map(([key, value]) => `${camelToKebab(key)}:${value}`)
    .join(';')
}

const dataToPublish = computed(() => {

    const text_1_element = props.announcementData?.fields?.text_1?.text ? `<div
            class="tw-text-sm tw-leading-6 tw-whitespace-nowrap "
            style="${styleString(propertiesToHTMLStyle(props.announcementData?.fields?.text_1?.block_properties, { toRemove: ['position', 'top', 'left'] }))}"
        >
            ${props.announcementData?.fields?.text_1?.text}
        </div>` : ''

    const button_element = props.announcementData?.fields?.button_1?.text ? `<button
        class="inline-flex items-center"
        style="${styleString(propertiesToHTMLStyle(props.announcementData?.fields.button_1?.container?.properties))}"
    >
        ${props.announcementData?.fields.button_1.text}
    </button>` : ''


    return `<div style="${styleString(propertiesToHTMLStyle(props.announcementData?.container_properties))}">
        <div class="tw-flex tw-gap-x-4 tw-items-center tw-justify-center tw-w-full tw-absolute tw-top-1/2 tw-left-1/2 -tw-translate-y-1/2 -tw-translate-x-1/2">
            ${text_1_element}
            ${button_element}
        </div>
    </div>`
})

defineExpose({
    dataToPublish,
    fieldSideEditor
})
    // <button
    //     v-if="announcementData?.fields?.button_1?.text"
    //     @click="() => onClickClose()"
    //     class="inline-flex items-center"
    //     style="${propertiesToHTMLStyle(props.announcementData?.fields.button_1?.container?.properties)}"
    // >
    //     ${props.announcementData?.fields?.button_1?.text}
    // </button>
</script>

<template>
    <template v-if="!isToSelectOnly">
        <div class="flex gap-x-4 items-center justify-center w-full absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2"
        >
            <div
                v-if="announcementData?.fields?.text_1"
                ref="_text_1"
                class="text-sm leading-6 whitespace-nowrap "
                v-html="announcementData?.fields.text_1.text"
                :style="propertiesToHTMLStyle(announcementData?.fields.text_1.block_properties, { toRemove: ['position', 'top', 'left'] })"
            >
    
            </div>

            <button
                v-if="announcementData?.fields?.button_1?.text"
                @click="() => onClickClose()"
                v-html="announcementData?.fields.button_1.text"
                class="inline-flex items-center"
                :style="propertiesToHTMLStyle(announcementData?.fields.button_1?.container?.properties)"
            >
            </button>
        </div>

        <!-- <pre>{{defaultFieldsData?.button_1?.container?.properties}}</pre> -->
        
        <!-- <Moveable
            v-if="isEditable"
            :target="_text_1"
            :draggable="true"
            :snapGap="true"
            :throttleDrag="1"
            :edgeDraggable="false"
            :snappable="true"
            :snapDirections="{top: true, left: true, bottom: true, right: true }"
            :elementSnapDirections='{"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true}'
            :startDragRotate="0"
            :throttleDragRotate="0"
            @drag="(e) => onDrag(e, announcementData?.fields.text_1.block_properties, _parentComponent)"
        /> -->
    
        <!-- Close Button -->
        <button
            ref="_buttonClose"
            @click="() => onClickClose()"
            type="button"
            class="p-2 -translate-x-1/2 -translate-y-1/2"
            :style="propertiesToHTMLStyle(announcementData?.fields.close_button.block_properties)"
        >
            <span class="sr-only">Dismiss</span>
            <span v-html="closeIcon"></span>
        </button>
        <Moveable
            v-if="isEditable"
            :target="_buttonClose"
            :draggable="true"
            :snapGap="true"
            :throttleDrag="1"
            :edgeDraggable="false"
            :snappable="true"
            :snapDirections="{top: true, left: true, bottom: true, right: true }"
            :elementSnapDirections='{"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true}'
            :startDragRotate="0"
            :throttleDragRotate="0"
            @drag="(e) => onDrag(e, announcementData?.fields.close_button.block_properties, _parentComponent)"
        />
    </template>

    <div
        v-else @click="() => emits('templateClicked', componentDefaultData)"
        class="inset-0 absolute"
    >
    </div>

</template>