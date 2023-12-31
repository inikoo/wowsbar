<script setup lang='ts'>
import { ref } from 'vue'
import { useCopyText } from '@/Composables/useCopyText'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCopy } from '@fal'
import {faEye, faEyeSlash} from '@far'
import {faTimesCircle,} from '@fas'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCopy, faEye, faEyeSlash,faTimesCircle)

const props = withDefaults(defineProps<{
    modelValue?: string | number
    placeholder?: string
    readonly?: boolean
    inputName?: string
    counter?: boolean
    maxLength?: number
    type?: string
    copyButton?: boolean
    autofocus?: boolean
    required?: boolean
    autocomplete?: string
    minValue?: string | number
    maxValue?: string | number
    caret?: boolean
    pattern?: string 
    clear?:boolean
}>(), {
    caret: true,
    type: 'text'
})

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const showPassword = ref(props.type)  // default is type = "text"
const handleEyeIcon = () => {
    showPassword.value = showPassword.value == 'text' ? 'password' : 'text'
}

const clearValue=()=>{
    emits('update:modelValue', '')
}

</script>

<template>
    <div class="bg-white w-full flex group relative ring-1 ring-gray-300 focus-within:ring-2 focus-within:ring-gray-500 rounded-md overflow-hidden">
        <div class="relative w-full">
            <input
                :value="modelValue"
                @input="(event: any) => emits('update:modelValue', event.target.value)"
                :id="inputName"
                :name="inputName"
                :readonly="readonly"
                :type="type == 'password' ? showPassword : type"
                :placeholder="placeholder"
                :maxlength="maxLength"
                :autofocus="autofocus"
                :min="minValue"
                :max="maxValue"
                :required="required"
                :pattern="pattern ?? type == 'number' ? '[0-9]*' : undefined"
                :autocomplete="autocomplete"
                class="remove-arrows-input bg-transparent py-2.5 block w-full
                    text-gray-600 sm:text-sm placeholder:text-gray-400
                    border-transparent
                    focus:ring-0 focus:ring-gray-500 focus:outline-0 focus:border-transparent
                    read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:focus:border-transparent read-only:focus:border-gray-300 read-only:text-gray-500
                "
                :class="[
                    caret ? '' : '[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none',
                    clear && modelValue.length ? 'pl-3 pr-7' : 'px-3'
                ]"
            />
            <div v-if="copyButton"
                class="flex justify-center items-center px-2 absolute inset-y-0 right-0 gap-x-1 cursor-pointer opacity-20 hover:opacity-75 active:opacity-100"
                @click="useCopyText(modelValue)"
            >
                <FontAwesomeIcon icon="fal fa-copy"
                    class="text-lg leading-none"
                    aria-hidden="true" />
            </div>
            <div v-if="clear && modelValue.length"
                class="flex justify-center items-center px-2 absolute inset-y-0 right-0 gap-x-1 cursor-pointer opacity-20 hover:opacity-75 active:opacity-100"
                @click="clearValue()"
            >
               <font-awesome-icon :icon="['fas', 'times-circle']"
                    class="text-lg leading-none"
                    aria-hidden="true" />
            </div>
        </div>

        <!-- Slot: for icon error/success/loading in field edit -->
        <div class="align-middle">
            <slot name="stateIcon"/>
        </div>


        <button v-if="props.type == 'password'"
            @click="handleEyeIcon"
            type="button"
            name="showPassword"
            class="-ml-px relative inline-flex items-center px-4 py-2 border-l border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-200 ">
            <FontAwesomeIcon v-show="showPassword == 'text'" aria-hidden="true" class="h-5 w-5 text-gray-400" icon="fa-regular fa-eye"/>
            <FontAwesomeIcon v-show="showPassword == 'password'" aria-hidden="true" class="h-5 w-5 text-gray-400" icon="fa-regular fa-eye-slash"/>
        </button>
    </div>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0; 
}

input[type=number] {
    -moz-appearance: textfield;
}
</style>
