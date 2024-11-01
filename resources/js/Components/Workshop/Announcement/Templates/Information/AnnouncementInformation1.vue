<script setup lang='ts'>
import Moveable from "vue3-moveable"
import { propertiesToHTMLStyle, onDrag } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { inject, onMounted, ref } from "vue"
import { closeIcon } from '@/Composables/useAnnouncement'
library.add(faTimes)

const props = defineProps<{
    announcementData: {
        fields: {

        }
        container_properties: {

        }
    }
    _parentComponent: Element
    isEditable?: boolean
    isToSelectOnly?: boolean
}>()

const emits = defineEmits<{
    (e: 'templateClicked'): void
}>()


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
                "value": 7
            },
            "topright": {
                "value": 7
            },
            "bottomleft": {
                "value": 7
            },
            "bottomright": {
                "value": 7
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
            "value": 70
        },
        "height": {
            "unit": "px",
            "value": "50"
        }
    },
    "background": {
        "type": "color",
        "color": "rgb(17,24,39)",
        "image": {
            "original": null
        }
    },
    "text": {
        "color": "rgb(255,255,255)",
        "fontFamily": "sans-serif"
    },
    "isCenterHorizontal": true
}

const defaultFieldsData = {
    "text_1": {
        "text": "<strong class=\"font-semibold\">GeneriCon 2023</strong><svg viewBox=\"0 0 2 2\" class=\"mx-2 inline h-0.5 w-0.5 fill-current\" aria-hidden=\"true\"><circle cx=\"1\" cy=\"1\" r=\"1\" /></svg>Join us in Denver from June 7 – 9 to see what’s coming next&nbsp;<span aria-hidden=\"true\">&rarr;</span>",
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
        "url": "https://example.com",
        "label": "Click me",
        "width": "full",
        "border": {
            "top": {
                "value": 0
            },
            "left": {
                "value": 0
            },
            "unit": "px",
            "color": "rgba(20, 20, 20, 1)",
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
        "target": "_blank",
        "background": {
            "type": "color",
            "color": "rgba(250, 250, 250, 1)",
            "image": {
                "original": null
            }
        },
        "text_color": "rgba(255, 255, 255, 1)",
        "block_properties": {
            "position": {
                "x": "20%",
                "y": "30%",
                "type": "absolute"
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
    code: 'announcement-information-1',
    fields: defaultFieldsData,
    container_properties: defaultContainerData
}

const _text_1 = ref(null)
const _buttonClose = ref(null)

const onClickClose = () => {
    window.parent.postMessage('close_button_click', '*');
}

</script>

<template>
    <!-- <div ref="_parentComponent" class="relative isolate flex items-center gap-x-6 bg-gray-50 px-6 py-2.5 sm:px-3.5 transition-all" :style="propertiesToHTMLStyle(announcementData.container_properties, { toRemove: styleToRemove})"> -->
        <template v-if="!isToSelectOnly">
            <div ref="_text_1" class="-translate-x-1/2 -translate-y-1/2 text-sm leading-6 whitespace-nowrap" v-html="announcementData.fields.text_1.text" :style="propertiesToHTMLStyle(announcementData.fields.text_1.block_properties)">
            
            </div>
            <Moveable
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
                @drag="(e) => onDrag(e, announcementData.fields.text_1.block_properties, _parentComponent)"
            />
            
            <!-- Close Button -->
            <button
                @click="() => onClickClose()"
                ref="_buttonClose"
                type="button"
                class="flex flex-1 justify-end p-2 -translate-x-1/2 -translate-y-1/2"
                :style="propertiesToHTMLStyle(announcementData.fields.close_button.block_properties)"
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
                @drag="(e) => onDrag(e, announcementData.fields.close_button.block_properties, _parentComponent)"
            />
        </template>
    <!-- </div> -->
    <div
        v-else @click="() => emits('templateClicked', componentDefaultData)"
        class="inset-0 absolute"
    >
    </div>
</template>