<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import Dialog from "./Dialog.vue";
import { useForm } from "@inertiajs/vue3";
import Button from "@/Components/Elements/Buttons/Button.vue";
import PureInput from "@/Components/Pure/PureInput.vue";
import SelectQuery from "@/Components/SelectQuery.vue";
import { trans } from "laravel-vue-i18n";

const props = defineProps<{
    show: boolean;
    attribut?: {
        href: string,
        type: string,
        id: string,
        workshop: string,
        target : string
  /*       content?: string  */
    }
}>();

console.log(props)
const emit = defineEmits(["close", "update"]);

// Reactive form data
const form = useForm({
    href: props.attribut?.href || "",
    type: props.attribut?.type || "internal",
    workshop: null,
    id: null,
    target : props.attribut?.href || "_parent",
});

// Watch for changes in the attribut prop
watch(() => props.attribut, (newValue) => {
    if (newValue) {
        form.href = newValue.href || "";
        form.type = newValue.type || "internal";
        form.workshop = newValue.workshop || null;
        form.id = newValue || null;
        form.target = newValue.target || "_parent"
    }
}, { immediate: true }); 

// Function to close the dialog
function closeDialog() {
    emit("close");
}

// Function to update the form and emit events
function update() {
    emit("update", form.data());
    emit("close");
}

// Function to handle link changes
const onChangeLink = (value) => {
    form.href = value.url;
    form.id = value.id;
    form.workshop = value.workshop;
};

// Options for link types
const options = [
    { label: 'Internal', value: 'internal' },
    { label: 'External', value: 'external' }
];

const target = [
    { label: 'In this page', value: '_parent' },
    { label: 'New Page', value: '_blank' }
]
</script>

<template>
    <Dialog title="Link Setting" :show="show" @close="closeDialog">
        <div class="flex flex-col gap-y-3">
            <div>
                <div class="select-none text-sm text-gray-600 mb-2">{{ trans("Type") }}</div>
                <div class="flex space-x-4">
                    <label v-for="option in options" :key="option.value" class="flex items-center space-x-2">
                        <input type="radio" :value="option.value" v-model="form.type" class="form-radio" />
                        <span>{{ option.label }}</span>
                    </label>
                </div>
            </div>

            <div>
                <div class="select-none text-sm text-gray-600 mb-2">{{ trans("Target") }}</div>
                <div class="flex space-x-4">
                    <label v-for="option in target" :key="option.value" class="flex items-center space-x-2">
                        <input type="radio" :value="option.value" v-model="form.target" class="form-radio" />
                        <span>{{ option.label }}</span>
                    </label>
                </div>
            </div>

           <!--  <div>
                <div class="select-none text-sm text-gray-600 mb-2">Label</div>
                <PureInput v-model="form.content" />
            </div> -->

            <!-- <div v-if="form.type === 'internal'">
                <div class="select-none text-sm text-gray-600 mb-2">Link</div>
                <SelectQuery 
                    fieldName="id" 
                    :object="true"
                    :urlRoute="route('grp.org.shops.show.web.webpages.index', {
                        organisation: route().params['organisation'],
                        shop: route().params['shop'],
                        website: route().params['website']
                    })" 
                    :value="form" 
                    :closeOnSelect="true" 
                    label="href" 
                    :onChange="onChangeLink"
                />
            </div> -->

            <div class="">
                <div class="select-none text-sm text-gray-600 mb-2">{{ trans("Link") }}</div>
                <PureInput v-model="form.href" :placeholder="trans('Enter a url')" />
            </div>
 
            <!-- Buttons -->
            <div class="mt-1 flex flex-row justify-end space-x-3">
                <Button :style="'tertiary'" label="cancel" @click="closeDialog">
                    {{ trans("Cancel") }}
                </Button>
                <Button @click="update" :style="'primary'" full label="Apply" />
            </div>
        </div>
    </Dialog>
</template>
