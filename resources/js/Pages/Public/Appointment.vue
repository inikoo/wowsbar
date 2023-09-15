<script setup lang="ts">
import { ref, watch } from "vue";
import { StarIcon } from "@heroicons/vue/20/solid";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import {
    CurrencyDollarIcon,
    GlobeAmericasIcon,
} from "@heroicons/vue/24/outline";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faRocketLaunch, faClock, faVideo } from "@/../private/pro-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";

library.add(faRocketLaunch, faClock, faVideo);

const data = {
    description: `
    <p>The Basic tee is an honest new take on a classic. The tee uses super soft, pre-shrunk cotton for true comfort and a dependable fit. They are hand cut and sewn locally, with a special dye technique that gives each tee it's own look.</p>
    <p>Looking to stock your closet? The Basic tee also comes in a 3-pack or 5-pack at a bundle discount.</p>
  `,
  meet : {
    customerService : 'Arya',
    duration : '30 mnt'
  },
  title : 'Discovery Call - Wowsbar',
  meetInformation : 'Web conferencing details provided upon confirmation.'
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

const selectedDate = ref(null);

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
</script>
<template>
    <div class="bg-white">
        <div class="pb-16 pt-6 sm:pb-24">
            <div
                class="mx-auto mt-8 max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8 border rounded-md"
            >
                <div
                    class="lg:grid lg:auto-rows-min lg:grid-cols-12 lg:gap-x-8"
                >
                    <!-- Image gallery -->
                    <div
                        class="mt-8 lg:col-span-5 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0"
                    >
                        <div class="flex justify-center align-middle p-20">
                            <FontAwesomeIcon
                                :icon="['far', 'rocket-launch']"
                                class="w-32 h-32"
                            />
                        </div>
                        <hr />
                        <div class="text-lg text-slate-400">{{ data.meet.customerService }}</div>
                        <div class="text-4xl font-medium">
                            {{ data.title }}
                        </div>
                        <div>
                            <div class="flex justify-start my-5 gap-3">
                            <div>
                                <font-awesome-icon
                                    :icon="['far', 'clock']"
                                    class="w-5 h-5"
                                />
                            </div>
                            <div>{{ data.meet.duration }}</div>
                        </div>
                        <div class="flex justify-start my-5 gap-3">
                            <div>
                                <font-awesome-icon :icon="['far', 'video']" />
                            </div>
                            <div>{{ data.meetInformation }}</div>
                        </div>
                        </div>
                       
                        <div class="my-10">
                            <h2 class="text-sm font-medium text-gray-900">
                                Description
                            </h2>

                            <div
                                class="prose prose-sm mt-4 text-gray-500"
                                v-html="data.description"
                            />
                        </div>
                    </div>

                    <div class="mt-8 lg:col-span-7">
                        <span class="text-lg font-medium text-gray-900"
                            >Select a Date & Time</span
                        >
                        <div class="p-2.5">
                            <VCalendar
                                expanded
                                :attributes="attrs"
                                @dayclick="handleDateClick"
                            />
                        </div>
                        <div v-if="!isNull(selectedDate)">
                            <div class="px-2.5">{{ getDate() }}</div>
                            <div class="px-2.5 my-4">
                                <div class="flex flex-wrap justify-start gap-3">
                                    <!-- Create buttons for hours using a v-for loop -->
                                    <button
                                        v-for="hour in hours"
                                        :key="hour"
                                        @click="selectHour(hour)"
                                        class="rounded-md border border-transparent bg-indigo-600 px-2 py-1 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
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
</template>
