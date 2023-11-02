<script setup lang="ts">
// T3
import { ref } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import { faExclamationCircle ,faCheckCircle} from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
library.add(faExclamationCircle,faCheckCircle)
const props = defineProps(['form', 'fieldName', 'fieldData'])

// const compareObjects = (objA, objB) => {
//     // Get the keys of objA and objB
//     const keysA = Object.keys(objA);
//     const keysB = Object.keys(objB);

//     // Check if the number of keys is the same
//     if (keysA.length !== keysB.length) {
//         return false;
//     }

//     // Check if the values for each key are equal
//     for (let key of keysA) {
//         if (objA[key] !== objB[key]) {
//             return false;
//         }
//     }

//     return true;
// }

</script>

<template>
    <div>
        <!-- <label class="text-base font-semibold text-gray-800 capitalize">{{ fieldName }}</label> -->
        <!-- <p class="text-xs text-gray-500 capitalize italic">{{ form[fieldName] }}</p> -->
        <fieldset class="relative select-none">
            <legend class="sr-only"></legend>
            <div class="flex items-center gap-x-8 gap-y-1 flex-wrap ">
                <!-- Radio: Compact -->
                <div v-if="fieldData.mode === 'compact'">
                    <RadioGroup v-model="form[fieldName]" @update:modelValue="form.errors[fieldName] = ''" class="mt-2">
                        <RadioGroupLabel class="sr-only">Choose the radio</RadioGroupLabel>
                        <div class="flex gap-x-1.5 gap-y-1 flex-wrap">
                            <RadioGroupOption as="template" v-for="(option, index) in fieldData.options" :key="option.value"
                                :value="option" v-slot="{ active, checked }">
                                <div
                                    :class="[
                                        'cursor-pointer focus:outline-none flex items-center justify-center rounded-md py-3 px-3 text-sm font-medium capitalize',
                                        active ? 'ring-2 ring-orange-600 ring-offset-2' : '',
                                        checked ? 'bg-orange-600 text-white hover:bg-orange-500' : 'ring-1 ring-inset ring-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                                    ]">
                                    <RadioGroupLabel as="span">{{ option.value }}</RadioGroupLabel>
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>

                <!-- Radio: Card -->
                <div v-else-if="fieldData.mode === 'card'">
                    <RadioGroup v-model="form[fieldName]" @update:modelValue="form.errors[fieldName] = ''">
                        <RadioGroupLabel class="text-base font-semibold leading-6 text-gray-700 sr-only">Select the radio</RadioGroupLabel>
                        <div class="flex gap-x-4 justify-around">
                            <RadioGroupOption as="template" v-for="(option, index) in fieldData.options" :key="option.value" :value="option.value" v-slot="{ active, checked }">
                                <div :class="[
                                    'relative flex cursor-pointer rounded-lg border bg-white py-2 px-3 shadow-sm focus:outline-none',
                                    active ? 'ring-2 ring-gray-600' : 'border-gray-300'
                                ]">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                        <RadioGroupLabel v-if="option.title" as="span" class="block text-sm font-medium text-gray-700 capitalize">{{ option.title }}</RadioGroupLabel>
                                        <RadioGroupDescription v-if="option.description" as="span" class="mt-1 flex items-center text-xs text-gray-400">{{ option.description }}</RadioGroupDescription>
                                        <RadioGroupDescription v-if="option.label" as="span" class="mt-6 text-xs font-medium text-gray-600">{{ option.label }}</RadioGroupDescription>
                                        </span>
                                    </span>
                                    <!-- <FontAwesomeIcon icon='far fa-check' :class="[!checked ? 'invisible' : '', 'h-4 w-4 text-gray-600']" aria-hidden="true" /> -->
                                    <span :class="[active ? 'border' : 'border-2', form[fieldName] == option.value ? 'border-gray-600' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-lg']" aria-hidden="true" />
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>

                <!-- Radio: Default -->
                <label :for="option.label + index" v-else v-for="(option, index) in fieldData.options"
                    :key="option.label + index" class="inline-flex gap-x-2.5 items-center cursor-pointer">
                    <input v-model="form[fieldName]" @change="form.errors[fieldName] = ''" :id="option.label + index" :key="option.label + index"
                        name="radioDefault" type="radio" :value="option.value" :checked="option.value == form[fieldName]"
                        class="h-4 w-4 border-gray-300 text-gray-600 focus:ring-0 focus:outline-none focus:ring-transparent cursor-pointer"
                    />
                    <div class="flex items-center gap-x-1.5 ">
                        <!-- <p class="text-sm font-medium leading-6 text-gray-700 capitalize">
                            {{ option.value }}
                        </p> -->
                        <span v-if="option.label" class="font-medium text-sm text-gray-600 capitalize">
                            {{ option.label }}
                            <!-- d -->
                        </span>
                    </div>
                </label>
            </div>

            <!-- State: Error icon -->
            <div v-if="form.errors[fieldName] || form.recentlySuccessful " class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <FontAwesomeIcon icon="fas fa-exclamation-circle" v-if="form.errors[fieldName]" class="h-5 w-5 text-red-500" aria-hidden="true" />
                <FontAwesomeIcon icon="fas fa-check-circle" v-if="form.recentlySuccessful" class="mt-1.5  h-5 w-5 text-green-500" aria-hidden="true"/>
            </div>
        </fieldset>

        <!-- State: Error description -->
        <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600">{{ form.errors[fieldName] }}</p>
    </div>
</template>
