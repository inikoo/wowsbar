<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import Multiselect from "@vueform/multiselect"
import { cloneDeep } from 'lodash'
import Popover from 'primevue/popover'

import { library } from "@fortawesome/fontawesome-svg-core"
import { faShieldAlt, faTimes, faTrash } from "@fas"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faFacebookF, faInstagram, faTiktok, faPinterest, faYoutube, faLinkedinIn } from "@fortawesome/free-brands-svg-icons";

library.add(faFacebookF, faInstagram, faTiktok, faPinterest, faYoutube, faLinkedinIn, faShieldAlt, faTimes, faTrash)

const props = defineProps<{
    modelValue: any,
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', value: {}): void
}>();
const _addop = ref();
const FeOptions = [
    {
        name: "Accounts",
        image: "/art/payments/accounts.png?v=2",
        value: "accounts",
    },
    {
        name: "Bank",
        image: "/art/payments/bank.png?v=2",
        value: "bank",
    },
    {
        name: "btree",
        image: "/art/payments/btree.png?v=2",
        value: "btree",
    },
    {
        name: "Cash",
        image: "/art/payments/cash.png?v=2",
        value: "Cash",
    },
    {
        name: "Checkout",
        image: "/art/payments/checkout.png?v=2",
        value: "Checkout",
    },
    {
        name: "Cond",
        image: "/art/payments/cond.png?v=2",
        value: "cond",
    },
    {
        name: "Hokodo",
        image: "/art/payments/hokodo.png?v=2",
        value: "hokodo",
    },
    {
        name: "Pastpay",
        image: "/art/payments/pastpay.png?v=2",
        value: "pastpay",
    },
    {
        name: "sofort",
        image: "/art/payments/sofort.png?v=2",
        value: "sofort",
    },
    {
        name: "Worldpay",
        image: "/art/payments/worldpay.png?v=2",
        value: "Worldpay",
    },
    {
        name: "Paypal",
        image: "/art/payments/paypal.png?v=2",
        value: "paypal",
    },
    {
        name: "Mastercard",
        image: "/art/payments/mastercard.png?v=2",
        value: "mastercard",
    },
    {
        name: "Visa",
        image: "/art/payments/visa.png?v=2",
        value: "visa",
    },
    {
        name: "Paypal",
        image: "/art/payments/paypal-white.png?v=2",
        value: "paypal-white",
    },
    {
        name: "Pastpay",
        image: "/art/payments/pastpay-white.png?v=2",
        value: "Pastpay-white",
    },
    {
        name: "Mastercard",
        image: "/art/payments/mastercard-white.png?v=2",
        value: "mastercard-white",
    },
    {
        name: "Visa",
        image: "/art/payments/visa-white.png?v=2",
        value: "visa-white",
    },
]

const addPayments = (value) => {
    let data = cloneDeep(props.modelValue.data);
    data.push(
        {
            name: value.name,
            value: value.name,
            image: value.image
        },
    );
    emits('update:modelValue', { data: data });
    _addop.value.hide();
};

const updatePayment = (index: number, value: any) => {
    let data = cloneDeep(props.modelValue.data);
    data[index] = {
        name: value.name,
        value: value.name,
        image: value.image
    };
    emits('update:modelValue', { data });
};

const deleteSocial = (event, index) => {
    event.stopPropagation();
    event.preventDefault();
    let set = cloneDeep(props.modelValue.data);
    set.splice(index, 1)
    emits('update:modelValue', { data: set });
}

const toggleAdd = (event: any) => {
    _addop.value.toggle(event);
}

</script>

<template>
    <div class="p-4">
        <template v-if="modelValue?.data">
            <div v-for="(item, index) of modelValue.data" :key="index" class="p-1">
                <div class="flex">
                    <Multiselect :modelValue="item" :options="FeOptions" :object="true" :canClear="false" :caret="false"
                        @update:modelValue="value => updatePayment(index, value)">
                        <template v-slot:singlelabel="{ value }">
                            <div class="flex items-center rounded-lg w-full p-2 m-2 mr-0">
                                <img class="w-12 h-12 rounded-full object-contain object-center group-hover:opacity-75"
                                    :src="value.image" alt="avatar">
                                <div class="ml-4 truncate" style="max-width: 150px;"> <!-- Set max width to truncate -->
                                    {{ value.name }}
                                </div>
                            </div>
                            <div class="flex items-center justify-center">
                                <FontAwesomeIcon :icon="['fas', 'trash']"
                                    class="text-red-500 hover:text-red-600 cursor-pointer p-3 transition-transform transform hover:scale-110"
                                    @click="(e) => deleteSocial(e, index)" />
                            </div>
                        </template>
                        <template v-slot:option="{ option }">
                            <div
                                class="flex items-center border-2 border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow w-full bg-gray-200 p-2 m-2">
                                <img class="w-12 h-12 rounded-full object-contain object-center group-hover:opacity-75"
                                    :src="option.image" alt="avatar">
                                <div class="ml-4 truncate" style="max-width: 150px;"> <!-- Set max width to truncate -->
                                    {{ option.name }}
                                </div>
                            </div>
                        </template>
                    </Multiselect>
                </div>
            </div>
        </template>
        <Button type="dashed" icon="fal fa-plus" label="Add Payments Method" full size="s" class="mt-2"
            @click="toggleAdd" />
        <Popover ref="_addop">
            <div class="grid grid-cols-5 gap-6">
                <div v-for="icon of FeOptions" :key="icon.value" class="pr-4">
                <div @click="()=>addPayments(icon)"
                    class="flex items-center border-2 border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow w-full bg-gray-200 p-2 m-2">
                    <img class="w-12 h-12 rounded-full object-contain object-center group-hover:opacity-75"
                        :src="icon.image" alt="avatar">
                    <div class="ml-4 truncate" style="max-width: 150px;"> 
                        {{ icon.name }}
                    </div>
                </div>
                </div>
            </div>
        </Popover>
    </div>
</template>

<style scoped></style>
