<script setup lang="ts">
import { ref } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faRocketLaunch,
    faClock,
    faVideo,
} from "@/../private/pro-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";

library.add(faRocketLaunch, faClock, faVideo);

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
<template layout="Public">
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
                        <div class="text-lg text-slate-400">
                            {{ Book.meet.customerService }}
                        </div>
                        <div class="text-4xl font-medium">
                            {{ data.title }}
                        </div>
                        <div>
                            <div class="flex justify-start my-2 gap-3">
                                <div>
                                    <font-awesome-icon
                                        :icon="['far', 'clock']"
                                        class="w-4 h-4"
                                    />
                                </div>
                                <div>{{ data.meet.duration }}</div>
                            </div>
                            <div class="flex justify-start my-2 gap-3">
                                <div>
                                    <font-awesome-icon
                                        :icon="['far', 'video']"
                                    />
                                </div>
                                <div>{{ data.meetInformation }}</div>
                            </div>
                        </div>

                        <div class="my-3">
                            <h2 class="text-sm font-medium text-gray-900">
                                Description
                            </h2>

                            <div
                                class="mt-1 mb-2 text-gray-500 text-xs"
                                v-html="data.description"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
