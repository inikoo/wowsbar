<script setup lang='ts'>
import Moveable from "vue3-moveable"
import { propertiesToHTMLStyle, onDrag } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { onMounted, ref } from "vue"
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

const _text_1 = ref(null)
const _buttonClose = ref(null)

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

const onClickClose = () => {
    window.parent.postMessage('close_button_click', '*');
}

</script>

<template>
    <template v-if="!isToSelectOnly">
        <div class="flex gap-x-4 items-center justify-center w-full">
            <div ref="_text_1" class="text-sm leading-6 whitespace-nowrap" v-html="announcementData.fields.text_1.text" :style="propertiesToHTMLStyle(announcementData.fields.text_1.block_properties, {toRemove: ['position', 'top', 'left']})">
    
            </div>
            <button
                @click="() => onClickClose()"
                v-html="announcementData.fields.button_1.text"
                class="inline-flex items-center"
                :style="propertiesToHTMLStyle(announcementData.fields?.button_1?.container?.properties)"
            >
            </button>
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
            ref="_buttonClose"
            type="button"
            class="p-2 -translate-x-1/2 -translate-y-1/2"
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

    <div
        v-else @click="() => emits('templateClicked', {container: defaultContainerData, fields: defaultFieldsData})"
        class="inset-0 absolute"
    >
    </div>

</template>