<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 27 Nov 2023 14:23:03 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaperPlane, faTimes } from '@fas'
import { faEnvelope, faPhone, faHouse } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { trans } from "laravel-vue-i18n";
import Popover from '@/Components/Utils/Popover.vue'
import { get, startCase } from 'lodash'
import Tag from "@/Components/Tag.vue"
import { ref, watch } from "vue"
import { notify } from "@kyvg/vue3-notification"
import axios from "axios"
import { faSpinnerThird } from '@fad'

library.add(faPaperPlane, faEnvelope, faPhone, faHouse, faSpinnerThird, faTimes)

const props = withDefaults(defineProps<{
    saveButton?: boolean
    changeWeeksValue?:Function
    option: {
        argument: {
            id: number
            name: string
            number_items: number
        }[]
    }
}>(), {
    saveButton: false
})

const emits = defineEmits();
const formMessage = ref('');
const loading = ref(false);
let timeoutId: any

const value = ref({
    quantity: get(props.option, ["constrains", "prospect_last_contacted", "argument", "quantity"], 1),
    unit: get(props.option, ["constrains", "prospect_last_contacted", "argument", "unit"], 'week')
})

const findIcon = (data) => {
    if (data == 'email') return 'fal fa-envelope'
    if (data == 'phone') return 'fal fa-phone'
    if (data == 'address') return 'fal fa-house'
}

/* 
const onChangeLastContact = async (closed) => {
    if (value.value.quantity && value.value.quantity > 0) {
        loading.value = true
        try {
            const response = await axios.get(
                route('org.crm.shop.prospects.mailshots.query.number-items', { ...route().params, query: props.option.slug }),
                { params: { ...value.value } }
            );
            onSuccessful(response.data, closed)

        } catch (error) {
            console.log(error)
            notify({
                title: "Failed",
                text: "Failed to update data, please try again",
                type: "error"
            });
        }
    } else formMessage.value = 'Please input the correct value'
    loading.value = false
}

const onSuccessful = (response, closed) => {
    const newData = { ...props.option }
    newData.constrains.prospect_last_contacted.argument.quantity = value.value.quantity
    newData.constrains.prospect_last_contacted.argument.unit = value.value.unit
    props.option.number_items = response.count
    emits("update:option", newData);
    if (closed) closed()
} */

const onChangeLastContact = async (closed) => {
    const newData = { ...props.option }
    newData.constrains.prospect_last_contacted.argument.quantity = value.value.quantity
    newData.constrains.prospect_last_contacted.argument.unit = value.value.unit
   /*  props.option.number_items = response.count */
    emits("update:option", newData);
    if (closed) closed()
}



/* const changeQuantity = (value) => {
    if (!value.target.value || value.target.value <= 0) formMessage.value = 'input valid days'
    else formMessage.value = ''
} */


watch(value.value, (newValue) => {
    if (!props.saveButton) {
        clearTimeout(timeoutId)
        timeoutId = setTimeout(() => {
            if(props.changeWeeksValue) props.changeWeeksValue(value.value)
            else  onChangeLastContact()
        }, 1200)
    }
})



</script>

<template>
    <div class="flex items-center gap-x-2">
        <!-- Icon: Email -->
        <div v-if="option.constrains.can_contact_by.fields.length > 0" class="inline-flex items-start">
            <div class="relative inline-flex">
                <!-- <Popover :width="'w-full'" position="right-[-60px] top-[-70px]" ref="_popover"> -->
                    <!-- <template #button> -->
                        <div class="relative" title="testing email">
                            <font-awesome-icon :icon="['fas', 'paper-plane']" />
                        </div>
                    <!-- </template> -->
                    <!-- <template #content="{ close: closed }">
                        <div class="flex gap-2">
                            <div v-for="(item, index) in option.constrains.can_contact_by.fields">
                                <Tag :theme="index + 1" size="sm">
                                    <template #label>
                                        <FontAwesomeIcon :icon="findIcon(item)" class='' aria-hidden='true' />
                                        {{ startCase(item) }}
                                    </template>

                                </Tag>
                            </div>
                        </div>

                    </template> -->
                <!-- </Popover> -->
            </div>
        </div>
        <p v-if="!option.has_arguments" class="text-gray-500">{{ trans('(Not contacted yet)') }}</p>
        <p v-else class="text-gray-500 whitespace-nowrap">
            {{ trans('(Last contacted at:') }}
        <div class="relative inline-flex">
            <Popover :width="'w-full'" position="right-[-60px]" ref="_popover">
                <template #button>
                    <div class="font-bold specialUnderlineOrg py-1 focus:outline-none focus:ring-0">
                        {{ get(option, ['constrains', 'prospect_last_contacted', 'argument', 'quantity'], 1) }}
                        {{ get(option, ['constrains', 'prospect_last_contacted', 'argument', 'unit'], 'week') }}
                    </div>
                </template>
                <template #content="{ close: closed }">
                    <div class="flex flex-col">
                        <div class="text-center font-semibold absolute top-1 right-2 text-xs cursor-pointer" @click="closed()">
                            <font-awesome-icon :icon="['fas', 'times']" />
                        </div>
                        <div class="text-center text-base font-semibold">{{ trans("Interval") }}</div>
                        <div class="flex gap-x-2">
                            <div class="w-20">
                                <PureInput v-model.number="value.quantity" type="number" :minValue="0" :caret="true"
                                    placeholder="days" required />
                            </div>
                            <div class="w-40">
                                <PureMultiselect v-model="value.unit" :options="['day', 'week', 'month']" required />
                            </div>
                        </div>
                        <div v-if="formMessage" class="text-red-500 text-xs py-2">{{ formMessage }}</div>

                        <div class="mt-2 text-gray-500 italic flex justify-between">
                            <p> {{ trans('Last contacted :') }} <span class="font-bold">
                                    {{ get(value, ['quantity']) }}
                                    {{ get(value, ['unit']) }}</span>
                            </p>
                            <div v-if="loading && !saveButton" class="flex">
                                <FontAwesomeIcon  icon='fad fa-spinner-third' class='animate-spin mx-2' aria-hidden='true' />
                                <span>saving...</span>
                            </div>
                            
                            <Button v-if="saveButton" label="Save" size="xxs" @click="props.onChangeValue ? props.changeWeeksValue(value.value) : onChangeLastContact(closed)"
                                :disabled="loading ? true : false" :loading="loading" />
                        </div>
                    </div>
                </template>
            </Popover>
        </div>
        )
        </p>
    </div>
</template>
