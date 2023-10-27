<script setup lang="ts">
import { ref, onMounted } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faRocketLaunch, faClock, faVideo } from "@far/";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";
import { loadCss } from "@/Composables/loadCss";

library.add(faRocketLaunch, faClock, faVideo);

const props = defineProps<{
    data: Object;
}>();
console.log("ini", props.data);
console.log("porpsd", props);

const Book = {
    description:
        "The Basic tee is an honest new take on a classic. The tee uses super soft, pre-shrunk cotton for true comfort and a dependable fit",
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
</script>
<template>
    <div style="margin: 20px;" >
        <div class="container border-2" style="max-width: 800px;">
        <div class="row">
            <div class="col-md-6 p-0">
                <div class="card text-center rounded-0 border-0">
                    <div
                        class="card-body"
                     
                    >
                        <div v-html="data.info"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-0">
                <div class="card text-center rounded-0 border-0">
                    <div class="card-body" style="border-left: 1px solid #d1d5db; height:100%">
                        <div class="bg-white rounded text-left">
                            <span v-html="data.title"></span>
                        </div>
                        <div>
                            <VCalendar
                                expanded
                                :attributes="attrs"
                                @dayclick="handleDateClick"
                            />
                        </div>
                        <div>
                            <div v-if="!isNull(selectedDate)">
                                <div class="px-2.5">{{ getDate() }}</div>
                                <div class="px-2.5 my-4">
                                    <div
                                        class="flex flex-wrap justify-start gap-3"
                                    >
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
    </div>
    </div>
    
</template>
