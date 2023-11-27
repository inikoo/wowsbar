<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 27 Nov 2023 14:23:03 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Popover, PopoverButton, PopoverPanel} from '@headlessui/vue'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faEnvelope, faAsterisk, faCodeBranch, faTags} from '@fal/'
import {library} from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import {trans} from "laravel-vue-i18n";
import {useLocaleStore} from "@/Stores/locale";

library.add(faEnvelope, faAsterisk, faCodeBranch, faTags)
const props = defineProps<{
    form: {
        [key: string]: {
            recipient_builder_type: string
            recipient_builder_data: {
                query: object
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
const locale = useLocaleStore()


</script>

<template>
    <div class="flex items-center gap-x-2">
                        <!-- Icon: Email -->
                        <div v-if="option.constrains.with === 'email'" class="inline-flex items-start">
                            <FontAwesomeIcon icon='fal fa-asterisk' class='h-2 text-red-500' aria-hidden='true'/>
                            <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true'/>
                        </div>

                        <p v-if="option.constrains.where?.[2]" class="text-gray-500">(Not contacted yet)</p>
                        <p v-if="option.constrains?.group" class="text-gray-500 whitespace-nowrap">
                            (Last contacted at:
                            <div class="relative inline-flex">
                                <Popover :popover-placement="'bottom-start'" v-slot="{ open }">
                                    <PopoverButton tabindex="-1">
                                        <div class="font-bold specialUnderlineOrg py-1 focus:outline-none focus:ring-0">
                                            {{ option.arguments.__date__?.value?.quantity ? option.arguments.__date__?.value?.quantity : 0 }} {{
                                                option.arguments.__date__?.value?.unit
                                            }}{{ option.arguments.__date__?.value?.quantity > 1 ? 's' : '' }})
                                        </div>
                                    </PopoverButton>

                                    <!-- Popover -->
                                    <transition>
                                        <PopoverPanel v-slot="{ close : closed }"
                                                      class="absolute w-64 max-w-md z-[99] mt-3 right-0 translate-x-2 transform py-3 px-4 bg-gray-100 ring-1 ring-gray-300 rounded-md shadow-md ">
                                            <div class="flex flex-col gap-y-2">
                                                <div class="text-center text-base font-semibold">Interval</div>
                                                <div class="flex gap-x-2">
                                                    <div v-if="option.arguments.__date__?.value" class="w-20">
                                                        <PureInput v-model="option.arguments.__date__.value.quantity" type="number" :minValue="1" :caret="false" placeholder="7"/>
                                                    </div>
                                                    <div v-if="option.arguments.__date__?.value?.unit" class="w-full">
                                                        <PureMultiselect v-model="option.arguments.__date__.value.unit" :options="['day', 'week', 'month']" required/>
                                                    </div>
                                                </div>
                                                <div class="mt-5 text-gray-500 italic flex justify-between">
                                                    <p>Last contacted <span class="font-bold">{{ option.arguments.__date__?.value?.quantity }} {{ option.arguments.__date__?.value?.unit }}</span></p>
                                                    <Button label="cancel" size="xxs" @click="closed" style="secondary"/>
                                                </div>
                                            </div>
                                        </PopoverPanel>
                                    </transition>
                                </Popover>
                            </div>
                        </p>
                    </div>

</template>
