<script setup lang="ts">
import { ref } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import PureRadio from '@/Components/Pure/PureRadio.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faEnvelope, faAsterisk)


const props = defineProps(['form', 'fieldName', 'fieldData'])


</script>

<template>
    <div>
        <table class="min-w-full divide-y divide-gray-300 border-b border-gray-200 text-xs">
            <thead>
                <tr class="text-left text-sm font-semibold text-gray-600">
                    <th scope="col" class="whitespace-nowrap pb-2.5 pl-4 pr-3 sm:pl-0">Name</th>
                    <th scope="col" class="whitespace-nowrap px-2 pb-2.5">Description</th>
                    <th scope="col" class="whitespace-nowrap px-2 pb-2.5">Prospects</th>
                    <th scope="col" class="relative whitespace-nowrap pb-2.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="option in fieldData.options.data" :key="option.id" class=""
                    :class="[
                        option.id == form[fieldName] ? 'bg-org-100 text-org-700' : '',
                        option.number_items < 1? 'bg-gray-100 text-gray-400' : 'text-gray-500'
                    ]">
                    <td class="whitespace-nowrap py-2 pl-2 pr-4 ">{{ option.name }}</td>
                    <td class="whitespace-nowrap">
                        <div class="flex items-center gap-x-1">
                            <div v-if="option.constrains.with === 'email'" class="inline-flex items-start" title="Prospect have email">
                                <!-- <FontAwesomeIcon icon='fal fa-asterisk' class='h-2 text-red-500' aria-hidden='true' /> -->
                                <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true' />
                            </div>
                            <p v-if="option.constrains.where?.[2]" class="">(Not contacted yet)</p>
                            <p v-if="option.constrains?.group" class=" whitespace-nowrap">
                                (Last contacted at: {{ option.arguments?.__date__?.value?.quantity }} {{ option.arguments?.__date__?.value?.unit }})
                            </p>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 text-center tabular-nums">{{ option.number_items }}</td>
                    <td class="relative whitespace-nowrap py-2 px-3 text-right font-medium">
                        <div v-if="option.number_items > 0" >
                            <label :for="'radioProspects' + option.id" class="bg-transparent absolute inset-0 cursor-pointer" />
                            <input v-model="form[fieldName]" :value="option.id" type="radio" :id="'radioProspects' + option.id" name="radioProspects" class="appearance-none text-org-500 focus:outline-org-500" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>