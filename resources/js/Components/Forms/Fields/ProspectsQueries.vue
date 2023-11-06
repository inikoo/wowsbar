<script setup lang="ts">
import { ref } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import PureRadio from '@/Components/Pure/PureRadio.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faEnvelope)


const props = defineProps(['form', 'fieldName', 'fieldData'])


</script>

<template>
    <div>
        <!-- <pre>{{ fieldData.options.data }}</pre> -->
        <RadioGroup
            v-model="form[fieldName]"
            by="id"
        >
            <RadioGroupLabel class="text-base font-semibold leading-6 text-gray-700 sr-only">Select the radio</RadioGroupLabel>
            <div class="flex gap-x-4 justify-around">
                <RadioGroupOption as="template" v-for="(option, index) in fieldData.options.data" :key="option.value" :value="option" v-slot="{ active, checked }">
                    <div :class="[
                        'relative flex cursor-pointer rounded-lg border py-2 px-3 shadow-sm focus:outline-none',
                        checked ? 'ring-2 ring-gray-600 bg-gray-100' : 'border-gray-300'
                    ]">
                        <span class="flex flex-col">
                            <RadioGroupLabel v-if="option.name" as="span" class="block text-sm font-medium text-gray-700 capitalize">{{ option.name }}</RadioGroupLabel>
                            <div class="flex gap-x-1 items-start text-gray-500 text-xs">
                                <FontAwesomeIcon v-if="option.constrains.with === 'email'" icon='fal fa-envelope' class='' aria-hidden='true' />
                                <span v-if="option.constrains?.where?.[2] === 'no-contacted'" class="leading-none">Not contacted yet.</span>
                                <span v-if="option.constrains?.group?.where?.[0] === 'last_contacted_at'" class="leading-none">Last contacted within {{ option.arguments?.__date__.value.quantity }} {{ option.arguments?.__date__.value.unit }} </span>
                            <!-- {{ option.constrains?.group?.where?.[0] === 'last_contacted_at' }} -->
                            </div>
                            <br>
                            <RadioGroupDescription as="span" class="mt-4 flex items-center text-xs text-gray-400">{{ option.number_items ?? 0 }}</RadioGroupDescription>
                        </span>
                        <!-- <FontAwesomeIcon icon='far fa-check' :class="[!checked ? 'invisible' : '', 'h-4 w-4 text-gray-600']" aria-hidden="true" /> -->
                        <!-- <span :class="[active ? 'border' : 'border-2', compareObjects(form[fieldName], option) ? 'border-gray-600' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-lg']" aria-hidden="true" /> -->
                    </div>
                </RadioGroupOption>
            </div>
        </RadioGroup>
    </div>
</template>