<script setup lang="ts">
import { ref, watch } from "vue";
import { faUpload } from "@fas";
import { set } from "lodash";

import { library } from "@fortawesome/fontawesome-svg-core";
import DatePicker from "primevue/datepicker";

library.add(faUpload);

const props = defineProps<{
    data: Record<string, any>;
    fieldName: string;
    fieldData: { value: string | string[] };
    bannerType: string;
}>();
const emits = defineEmits(["update:data"]);

// Helper to get a nested value from the object
const getFormValue = (data: any, fieldKeys: string | string[]) => {
    const keys = Array.isArray(fieldKeys) ? fieldKeys : [fieldKeys];
    const date = keys.reduce((acc, key) => acc && acc[key], data) ?? null;
    return date ? new Date(date) : null; // Ensure the value is a Date object
};

// Initialize the `date` value as a Date object
const date = ref(getFormValue(props.data, props.fieldData.value));

// Watch for changes in `date` and update `props.data` as a string
watch(date, (newDate) => {
    const dateString = newDate instanceof Date ? newDate.toISOString() : ""; // Convert to ISO string
    const localData = { ...props.data };
    if (Array.isArray(props.fieldName)) {
        set(localData, props.fieldName, dateString);
    } else {
        localData[props.fieldName] = dateString;
    }

    emits("update:data", localData);
});
</script>

<template>
    <div class="py-5">
        <div class="mb-2">Date</div>
        <DatePicker
            :modelValue="date"
            @update:model-value="(e) => (date = e)"
            showTime
            :minDate="new Date()"
            hourFormat="24"
            fluid
        />
    </div>
</template>
