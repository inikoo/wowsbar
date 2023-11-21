<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import PureRadio from '@/Components/Pure/PureRadio.vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk, faCodeBranch, faTags } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
library.add(faEnvelope, faAsterisk, faCodeBranch, faTags)


const props = defineProps<{
    form: {
        [key: string]: {
            recipient_builder_type: string
            recipient_builder_data: {
                query: number
            }
        }
    }
    fieldName: string
    tabName: string  // 'query', 'custom', 'select'
    fieldData: any
    options: {
        data: {
            id: number
            name: string
            number_items: number
        }[]
    }
}>()

const emits = defineEmits<{
    (e: 'onUpdate'): void
}>()

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
                <tr v-for="option in options.data" :key="option.id" class=""
                    :class="[
                        option.id == form[fieldName].recipient_builder_data.query ? 'bg-org-50 text-gray-600' : '',
                        option.number_items < 1? 'bg-gray-100 text-gray-400' : 'text-gray-500'  // If the prospects is 0
                    ]">
                    <td class="py-2 pl-2 pr-4 ">{{ option.name }}</td>
                    <td class="">
                      <!-- <div class="flex items-center gap-x-1">
                      {{  option }}
                            <div v-if="option.constrains.with === 'email'" class="inline-flex items-start" title="Prospect have email">
                              
                                <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true' />
                            </div>
                            <p v-if="option.constrains.where?.[2]" class="">(Not contacted yet)</p>
                            <p v-if="option.constrains?.group" class="">
                                (Last contacted at: {{ option.arguments?.__date__?.value?.quantity }} {{ option.arguments?.__date__?.value?.unit }})
                            </p>
                        </div>  -->

                        <div class="flex items-center gap-x-2">
                <!-- Icon: Email -->
                <div v-if="option.constrains.with === 'email'" class="inline-flex items-start">
                    <FontAwesomeIcon icon='fal fa-asterisk' class='h-2 text-red-500' aria-hidden='true' />
                    <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true' />
                </div>

                <p v-if="option.constrains.where?.[2]" class="text-gray-500">(Not contacted yet)</p>
                <p v-if="option.constrains?.group" class="text-gray-500 whitespace-nowrap">
                    (Last contacted at:
                    <div class="relative inline-flex">
                        <Popover :popover-placement="'bottom-start'" v-slot="{ open }">
                            <PopoverButton tabindex="-1">
                                <div class="font-bold specialUnderlineOrg py-1 focus:outline-none focus:ring-0">
                                    {{ option.arguments.__date__?.value?.quantity ? option.arguments.__date__?.value?.quantity : 0  }} {{ option.arguments.__date__?.value?.unit }}{{ option.arguments.__date__?.value?.quantity > 1 ? 's' : '' }})
                                </div>
                            </PopoverButton>

                            <!-- Popover -->
                            <transition>
                                <PopoverPanel class="absolute w-64 max-w-md z-[99] mt-3 right-0 translate-x-2 transform py-3 px-4 bg-gray-100 ring-1 ring-gray-300 rounded-md shadow-md ">
                                    <div class="flex flex-col gap-y-2">
                                        <div class="text-center text-base font-semibold">Select your time</div>
                                        <div class="flex gap-x-2">
                                            <div v-if="option.arguments.__date__?.value" class="w-20">
                                                <PureInput v-model="option.arguments.__date__.value.quantity" type="number" :minValue="1" :caret="false" placeholder="7" />
                                            </div>
                                            <div v-if="option.arguments.__date__?.value?.unit" class="w-full">
                                                <PureMultiselect v-model="option.arguments.__date__.value.unit" :options="['day', 'week', 'month']" required />
                                            </div>
                                        </div>
                                        <div class="mt-5 text-gray-500 italic flex justify-between">
                                            <p>Will be send in <span class="font-bold">{{ option.arguments.__date__?.value?.quantity }} {{  option.arguments.__date__?.value?.unit }}</span></p>
                                            <Button label="Set" size="xxs" />
                                        </div>
                                    </div>
                                </PopoverPanel>
                            </transition>
                        </Popover>
                    </div>
                </p>
            </div>
                        
                    </td>
                    <td class="px-2 py-2 text-center tabular-nums">{{ option.number_items }}</td>
                    <td class="relative py-2 px-3 text-right font-medium">
                        <div v-if="option.number_items > 0" >
                            <label :for="'radioProspects' + option.id" class="bg-transparent absolute inset-0 cursor-pointer" />
                            <input v-model="form.recipients.recipient_builder_data.query" :value="option.id" type="radio" :id="'radioProspects' + option.id" name="radioProspects" class="appearance-none ring-1 ring-gray-400 text-org-600 focus:border-0 focus:outline-none focus:ring-0" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>