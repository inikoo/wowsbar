<script setup lang="ts">
import { ref, onMounted } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faRocketLaunch,
    faClock,
    faVideo,
} from "@/../private/pro-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";
import { loadCss } from '@/Composables/loadCss';

library.add(faRocketLaunch, faClock, faVideo);

const props = defineProps<{
    title: String
    breadcrumbs: String
    calendars: Object
    content: Array
}>()



const Book = {
    description: "The Basic tee is an honest new take on a classic. The tee uses super soft, pre-shrunk cotton for true comfort and a dependable fit",
    meet: {
        customerService: "Arya",
        duration: "30 mnt",
    },
    title: "Discovery Call - Wowsbar",
    meetInformation: "Web conferencing details provided upon confirmation.",
};
const attrs = ref([
    {
        key: "today",
        highlight: {
            color: "purple",
            fillMode: "outline",
        },
        dates: new Date(),
    },
    {
        key: "tgl2",
        highlight: {
            color: "purple",
            fillMode: "outline",
        },
        dates: new Date(2023, 8, 2),
    },
    {
        key: "tgl7",
        highlight: {
            color: "purple",
            fillMode: "outline",
        },
        dates: new Date(2023, 8, 4),
    },
    {
        key: "tgl6",
        highlight: {
            color: "purple",
            fillMode: "outline",
        },
        dates: new Date(2023, 8, 8),
    },
    {
        key: "tgl4",
        highlight: {
            color: "purple",
            fillMode: "outline",
        },
        dates: new Date(2023, 8, 20),
    },
]);

const hours = [
    "07.00 Am",
    "08.00 Am",
    "09.00 Am",
    "10.00 Am",
    "11.00 Am",
    "12.00 Am",
    "13.00 Am",
];

const selectedDate = ref(0);

const handleDateClick = (date) => {
    if (date.attributes.length > 0) {
        if (!isNull(selectedDate.value)) {
            attrs.value[selectedDate.value].highlight.fillMode = "outline";
        }
        const index = attrs.value.findIndex(
            (item) => date.attributes[0].key == item.key
        );
        if (index >= 0) {
            attrs.value[index].highlight.fillMode = "solid";
            selectedDate.value = index;
        }
    }
};

const getDate = () => {
    if (!isNull(selectedDate.value)) {
        return useFormatTime(attrs.value[selectedDate.value].dates);
    }
    return ""; // Handle when no date is selected
};


const css = props.content[0] ? loadCss(props.content[0].css) : []
let dynamicClasses = '';
onMounted(() => {
    // Generate dynamic CSS classes based on the parsed styles
    for (const selector in css) {
        let classString = '';
        for (const property in css[selector]) {
            classString += `${property}: ${css[selector][property]};`;
        }
        dynamicClasses += `${selector} { ${classString} } `;
    }

    // Append the dynamic styles to the <style> block using a new style element
    const styleElement = document.createElement('style');
    styleElement.textContent = dynamicClasses;
    document.head.appendChild(styleElement);
});

</script>
<template layout="Public">
    <!-- <div v-if="content[0]">
        <div class="flex justify-center">
            <div class="mx-auto mt-8 px-4 sm:px-6  lg:px-8 flex"> -->
    <!-- <div v-html="content[0].html" :class="dynamicClasses"></div> -->
    <!-- <div class="bg-white">
                       
                </div>
            </div>
        </div>

    </div> -->

    <div class="bg-white">
        <div class="pb-16 pt-6 sm:pb-24">
            <div class="mx-auto mt-8 max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8 border rounded-md">
                <div class="lg:grid lg:auto-rows-min lg:grid-cols-12 lg:gap-x-8">
                    <div class="mt-8 lg:col-span-5 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0">
                        <div class="flex justify-center align-middle p-20">
                            <img src="https://dummyimage.com/" data-gjs-removable="false" class="w-32 h-32  p-2"
                                alt="Description of the image">
                        </div>
                        <hr />
                        <div class="text-lg text-slate-400">
                            Hi you can create appointment
                        </div>
                        <div class="text-xl font-medium">
                            Request a one-to-one appointemt with us
                        </div>
                        <div>
                            <div class="flex justify-start my-2 gap-3 items-center">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em"
                                        viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z" />
                                    </svg>
                                </span>
                                <div class="leading-none">30 min</div>
                            </div>
                        </div>

                        <div class="my-3">
                            <h2 class="text-sm font-medium text-gray-900">
                                Description
                            </h2>

                            <p class="mt-1 mb-2 text-gray-500 text-xs"> Lorem Ipsum is simply dummy text of the printing and
                                typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the
                                1500s, when an unknown printer took a galley of type and scrambled it to make a type
                                specimen book. It has survived not only five centuries, but also the leap into electronic
                                typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                                release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        </div>
                    </div>
                    <div class="mt-8 lg:col-span-7">
                        <div class="mx-auto mt-8 max-w-2xl px-4 sm:px-6  lg:px-8">
                            <div class="mt-8 lg:col-span-5 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0">
                                <div class="mt-8 lg:col-span-7">
                                    <span class="text-lg font-medium text-gray-900">Select a Date & Time</span>
                                    <div class="p-2.5">
                                        <VCalendar expanded :attributes="attrs" @dayclick="handleDateClick" />
                                    </div>
                                    <div v-if="!isNull(selectedDate)">
                                        <div class="px-2.5">{{ getDate() }}</div>
                                        <div class="px-2.5 my-4">
                                            <div class="flex flex-wrap justify-start gap-3">
                                                <button v-for="hour in hours" :key="hour" @click="selectHour(hour)"
                                                    class="rounded-md border border-transparent bg-indigo-600 px-2 py-1 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                    {{ hour }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
