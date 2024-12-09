<script setup lang='ts'>
import Moveable from "vue3-moveable"
import { propertiesToHTMLStyle, onDrag, styleToString } from '@/Composables/usePropertyWorkshop'
// import type { BlockProperties, LinkProperties } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { computed, ref, onMounted, onBeforeUnmount } from "vue"
import { closeIcon } from '@/Composables/useAnnouncement'
import type { BlockProperties, LinkProperties } from "@/types/Announcement"
import { inject } from "vue"
library.add(faTimes)

const props = defineProps<{
    announcementData?: {
        fields: {
            text_1: {
                text: string
                block_properties: BlockProperties
            }
            button_1: {
                link: LinkProperties
                text: string
                container: {
                    properties: BlockProperties
                }
            }
            countdown: {
                date: string
                expired_text?: string
            }
        }
        container_properties: BlockProperties
    }
    _parentComponent?: Element
    isEditable?: boolean
    isToSelectOnly?: boolean
}>()


const emits = defineEmits<{
    (e: 'templateClicked', value: typeof componentDefaultData): void
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
                key: ['container_properties'],
                type: "properties"
            },
        ]
    },
    {
        name: "Main title",
        icon: {
            icon: "fal fa-text",
            tooltip: "Main title"
        },
        replaceForm: [
            {
                key: ['fields', 'text_1'],
                type: "editorhtml",
                props_data: {
                    toogle: [
                        'heading', 'fontSize', 'bold', 'italic', 'underline', "fontFamily",
                        'alignLeft', 'alignRight', "link",
                        'alignCenter', 'undo', 'redo', 'highlight', 'color', 'clear'
                    ]
                }
            }
        ]
    },
    {
        name: "Countdown",
        icon: {
            icon: "fal fa-stopwatch-20",
            tooltip: "Time countdown"
        },
        replaceForm: [
            {
                key: ['fields', 'countdown'],
                type: "countdown",
                props_data: {
                    noToday: true
                }
            }
        ]
    },
    {
        name: "Button",
        icon: {
            icon: "fal fa-hand-pointer",
            tooltip: "Main title"
        },
        replaceForm: [
            {
                key: ['fields', 'button_1'],
                type: "button"
            }
        ]
    },
]

// Data: Container
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
            "value": 8
        },
        "left": {
            "value": 20
        },
        "unit": "px",
        "right": {
            "value": 20
        },
        "bottom": {
            "value": 8
        }
    },
    "position": {
        "x": "0%",
        "y": "0px",
        "type": "relative"
    },
    "dimension": {
        "width": {
            "unit": "%",
            "value": 100
        },
        "height": {
            "unit": "%",
            "value": 100
        }
    },
    "background": {
        "type": "color",
        "color": "linear-gradient(90deg, #f1e1c2 0%, #fcbc98 35%, #f9cdc3 57%, #facefb 83%)",
        "image": {
            "original": null
        }
    },
    "text": {
        "color": "rgba(10,10,10,1)",
        "fontFamily": "'Raleway', sans-serif"
    },
    "isCenterHorizontal": false,
    "additional_style": {
        display: "grid",
        "grid-template-columns": "repeat(3, 1fr)",
        "align-items": "center"
    }
}

// Data: Text, Button, Close Button
const defaultFieldsData = {
    "text_1": {
        "text": "<p><span style=\"font-family: 'Quicksand', sans-serif; font-size: 20px; color: #a810a8\"><strong>Special christmas sale✨!</strong></span></p>",
        // "block_properties": {
        //     "position": {
        //         "x": "50%",
        //         "y": "50%",
        //         "type": "absolute"
        //     }
        // }
    },
    // "text_2": {
    //     "text": "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet nisi at elit venenatis fringilla. Cras ut semper quam, sit.</p>",
    //     "block_properties": {
    //         "position": {
    //             "x": "20%",
    //             "y": "30%",
    //             "type": "absolute"
    //         }
    //     }
    // },
    "button_1": {
        "link": {
            "type": "external",
            "url": "#",
            "id": 9,
            "workshop_route": ""
        },
        "text": 'Claim Now!',
        "container": {
            "properties": {
                "text": {
                    "color": "rgba(255,255,255,1)",
                    "fontFamily": null
                },
                "background": {
                    "type": "color",
                    "color": "rgba(147, 51, 234, 1)",
                    "image": {
                        "original": null
                    }
                },
                "padding": {
                    "unit": "px",
                    "top": {
                        "value": 5
                    },
                    "left": {
                        "value": 15
                    },
                    "right": {
                        "value": 15
                    },
                    "bottom": {
                        "value": 5
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
                            "value": 7
                        },
                        "topleft": {
                            "value": 7
                        },
                        "bottomright": {
                            "value": 7
                        },
                        "bottomleft": {
                            "value": 7
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
    // "close_button": {
    //     "size": "0.5",
    //     "block_properties": {
    //         "text": {
    //             "color": "rgba(0, 0, 0, 0.5)",
    //         },
    //         "position": {
    //             "x": "97%",
    //             "y": "50%",
    //             "type": "absolute"
    //         }
    //     }
    // }
}

// To select on select templates
const componentDefaultData = {
    code: 'announcement-promo-2-countdown',
    fields: defaultFieldsData,
    container_properties: defaultContainerData
}

// const onClickClose = () => {
//     window.parent.postMessage('close_button_click', '*')
// }


const compiled_layout = computed(() => {
    const script = "<script> const initialTime = 1000 * 60 * 60 * 24 * 5; const endTime = new Date('" + props.announcementData?.fields?.countdown?.date + "').getTime() + initialTime; let timer = null; const parseTime = (time) => ({ tens: Math.floor(time / 10), ones: time % 10, }); const updateCountdown = () => { const now = new Date().getTime(); const timeLeft = endTime - now; if (timeLeft <= 0) { clearInterval(timer); document.getElementById('countdown-days').textContent = '00'; document.getElementById('countdown-hours').textContent = '00'; document.getElementById('countdown-minutes').textContent = '00'; document.getElementById('countdown-seconds').textContent = '00'; return; } const days = parseTime(Math.floor(timeLeft / (1000 * 60 * 60 * 24))); const hours = parseTime(Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))); const minutes = parseTime(Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))); const seconds = parseTime(Math.floor((timeLeft % (1000 * 60)) / 1000)); document.getElementById('countdown-days').textContent = `${days.tens}${days.ones}`; document.getElementById('countdown-hours').textContent = `${hours.tens}${hours.ones}`; document.getElementById('countdown-minutes').textContent = `${minutes.tens}${minutes.ones}`; document.getElementById('countdown-seconds').textContent = `${seconds.tens}${seconds.ones}`; }; timer = setInterval(updateCountdown, 1000); updateCountdown(); <\/script>"


    const button_element = props.announcementData?.fields?.button_1?.text ? `
        <div class="tw-justify-self-end">
            <a href="${props.announcementData?.fields.button_1.link.href || '#'}" target="${props.announcementData?.fields.button_1.link.target}" style="${styleToString(propertiesToHTMLStyle(props.announcementData.fields.button_1.container.properties))}">
                ${props.announcementData.fields.button_1.text}
            </a>
        </div>` : ''


    return `
    ${script}
    <div id="#wowsbar_announcement" style="${styleToString(propertiesToHTMLStyle(props.announcementData?.container_properties))}">
        <div class="" style="${styleToString(propertiesToHTMLStyle(props.announcementData?.fields.text_1.block_properties))}">
            ${props.announcementData?.fields.text_1.text}
        </div>

        <div class="tw-grid tw-grid-cols-4  tw-gap-x-2 tw-font-sans tw-mx-auto tw-mt-1">
            <div class="tw-flex tw-flex-col tw-items-center">
                <div id="countdown-days" class="tw-text-base tw-bg-white tw-w-fit tw-border tw-border-gray-200 tw-flex tw-justify-center tw-overflow-hidden tw-relative tw-rounded-md tw-py-1 tw-px-2 tw-tabular-nums">
                    00
                </div>
                <div class="tw-text-xs tw-opacity-60">Days</div>
            </div>
            <div class="tw-flex tw-flex-col tw-items-center">
                <div id="countdown-hours" class="tw-text-base tw-bg-white tw-w-fit tw-border tw-border-gray-200 tw-flex tw-justify-center tw-overflow-hidden tw-relative tw-rounded-md tw-py-1 tw-px-2 tw-tabular-nums">
                    00
                </div>
                <div class="tw-text-xs tw-opacity-60">Hours</div>
            </div>
            <div class="tw-flex tw-flex-col tw-items-center">
                <div id="countdown-minutes" class="tw-text-base tw-bg-white tw-w-fit tw-border tw-border-gray-200 tw-flex tw-justify-center tw-overflow-hidden tw-relative tw-rounded-md tw-py-1 tw-px-2 tw-tabular-nums">
                    00
                </div>
                <div class="tw-text-xs tw-opacity-60">Minutes</div>
            </div>
            <div class="tw-flex tw-flex-col tw-items-center">
                <div id="countdown-seconds" class="tw-text-base tw-bg-white tw-w-fit tw-border tw-border-gray-200 tw-flex tw-justify-center tw-overflow-hidden tw-relative tw-rounded-md tw-py-1 tw-px-2 tw-tabular-nums">
                    00
                </div>
                <div class="tw-text-xs tw-opacity-60">Seconds</div>
            </div>
        </div>

        ${button_element}
    </div>`
})

const openFieldWorkshop = inject('openFieldWorkshop')

defineExpose({
    compiled_layout,
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
        <div @click="() => (openFieldWorkshop = 1)" class="announcement-component-editable" v-html="announcementData?.fields.text_1.text" :style="propertiesToHTMLStyle(announcementData?.fields?.text_1.block_properties)">
            
        </div>

        <div @click="() => (openFieldWorkshop = 2)" class="announcement-component-editable grid grid-cols-4 gap-x-2 font-sans mx-auto mt-1">
            <div class="flex flex-col items-center">
                <div id="countdown-days" class="text-base bg-white w-fit border border-gray-200 flex justify-center overflow-hidden relative rounded-md py-1 px-2 tabular-nums">
                    06
                </div>
                <div class="text-xs opacity-60">Days</div>
            </div>

            <div class="flex flex-col items-center">
                <div id="countdown-hours" class="text-base bg-white w-fit border border-gray-200 flex justify-center overflow-hidden relative rounded-md py-1 px-2 tabular-nums">
                    23
                </div>
                <div class="text-xs opacity-60">Hours</div>
            </div>

            <div class="flex flex-col items-center">
                <div id="countdown-minutes" class="text-base bg-white w-fit border border-gray-200 flex justify-center overflow-hidden relative rounded-md py-1 px-2 tabular-nums">
                    59
                </div>
                <div class="text-xs opacity-60">Minutes</div>
            </div>

            <div class="flex flex-col items-center">
                <div id="countdown-seconds" class="text-base bg-white w-fit border border-gray-200 flex justify-center overflow-hidden relative rounded-md py-1 px-2 tabular-nums">
                    00
                </div>
                <div class="text-xs opacity-60">Seconds</div>
            </div>
        </div>

        <div @click="() => (openFieldWorkshop = 3)" class="announcement-component-editable justify-self-end">
            <div :href="announcementData?.fields.button_1.link.href || '#'" :target="announcementData?.fields.button_1.link.target" v-html="announcementData?.fields.button_1.text" :style="propertiesToHTMLStyle(announcementData?.fields.button_1.container.properties)">
            </div>
        </div>
        
    </template>

    <div v-else @click="() => emits('templateClicked', componentDefaultData)" class="inset-0 absolute">
    </div>

</template>