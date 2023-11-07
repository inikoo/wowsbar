<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useLocaleStore } from "@/Stores/locale"
import Button from '@/Components/Elements/Buttons/Button.vue'
import { reactive } from 'vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()
const dataActionList = reactive({
    quantity: 1,
    unit: 'days'
})


</script>

<template>
    <!-- {{  countDate() }} -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(number_items)="{ item: prospect_list }">
            <span class="tabular-nums">{{ locale.number(prospect_list['number_items']) }}</span>
        </template>

        <template #cell(description)="{ item: prospect_list }">
            <!-- {{ prospect_list.constrains }}
            <br />================== -->
            <div class="flex items-center gap-x-2">
                <div v-if="prospect_list.constrains.with === 'email'" class="inline-flex items-start">
                    <FontAwesomeIcon icon='fal fa-asterisk' class='h-2 text-red-500' aria-hidden='true' />
                    <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true' />
                </div>
                <p v-if="prospect_list.constrains.where?.[2]" class="text-gray-500">(Not contacted yet)</p>
                <p v-if="prospect_list.constrains?.group" class="text-gray-500 whitespace-nowrap">(Last contacted at: {{ prospect_list.arguments.__date__?.value?.quantity }} {{ prospect_list.arguments.__date__?.value?.unit }})</p>
            </div>
        </template>

        <template #cell(actions)="{ item: prospect_list }">
            <div class="flex gap-x-2 items-center">
                <Button :style="prospect_list.arguments ? 'secondary' : 'tertiary'" :label="`${prospect_list.arguments.__date__?.value?.quantity ?? '0'} ${prospect_list.arguments.__date__?.value?.unit ?? 'Days'}`" size="xs" />
                
                <!-- Popover -->
                <div class="relative">
                    <Popover :popover-placement="'bottom-start'" v-slot="{ open }">
                        <PopoverButton tabindex="-1">
                            <FontAwesomeIcon icon='fal fa-edit' class='text-gray-500' aria-hidden='true' />
                        </PopoverButton>

                        <transition>
                            <PopoverPanel class="absolute z-[99] mt-3 right-0 translate-x-2 transform py-3 px-4 bg-gray-100 ring-1 ring-gray-300 rounded-md shadow-md ">
                                <div class="flex flex-col w-64 gap-y-2">
                                    <div class="text-center text-base font-semibold">Select your time</div>
                                    <div class="flex gap-x-2">
                                        <div v-if="prospect_list.arguments.__date__?.value" class="w-20">
                                            <PureInput v-model="prospect_list.arguments.__date__.value.quantity" type="number" :minValue="1" :caret="false" />
                                        </div>
                                        <div v-if="prospect_list.arguments.__date__?.value?.unit" class="w-full">
                                            <PureMultiselect v-model="prospect_list.arguments.__date__.value.unit" :options="['Days', 'Weeks', 'Months']" required />
                                        </div>
                                    </div>
                                    <div class="mt-5 text-gray-500 italic flex justify-between">
                                        <p>Will be send in <span class="font-bold">{{ prospect_list.arguments.__date__?.value?.quantity }} {{  prospect_list.arguments.__date__?.value?.unit }}</span></p>
                                        <Button label="Set" size="xxs" />
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </div>
            </div>
        </template>
    </Table>
</template>


