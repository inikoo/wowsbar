<script setup lang="ts">
import { ref, onMounted } from "vue"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import IconPicker from "./IconPicker/IconPicker.vue"
const props = defineProps<{
    data: Object
}>()
const emits = defineEmits();



</script>

<template>
    <div class="mt-5 flex gap-2">
        <div class="flex-1" style="width:87%;">
            <div
                class="flex shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                <span class="relative flex select-none p-2.5  border-gray-500 items-center pl-3 text-gray-500 sm:text-sm">
                    <IconPicker :data="data" />
                </span>
                <input v-model="data.value" type="text"
                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                    placeholder="label" />
            </div>
        </div>
        <div class="flex justify-center align-middle">
            <button @click.prevent="emits('OnDelete')"
                class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm">
                <font-awesome-icon :icon="['fas', 'trash']" />
            </button>

        </div>
    </div>
</template>
