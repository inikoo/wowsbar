<script setup lang="ts">
import { ref } from 'vue';
import Button from '@/Components/Elements/Buttons/Button.vue';
import Multiselect from "@vueform/multiselect";
import { cloneDeep } from 'lodash';
import Popover from 'primevue/popover';

import { library } from "@fortawesome/fontawesome-svg-core";
import { faShieldAlt, faTimes, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { 
    faFacebookF, faInstagram, faTiktok, faPinterest, faYoutube, faLinkedinIn 
} from "@fortawesome/free-brands-svg-icons";

library.add(faFacebookF, faInstagram, faTiktok, faPinterest, faYoutube, faLinkedinIn, faShieldAlt, faTimes, faTrash);

const props = defineProps<{
    modelValue: { data: Array<{ name: string, value: string, image: string }> },
    background?: string
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', value: { data: Array<{ name: string, value: string, image: string }> }): void
}>();

const _addop = ref(null);
const _editop = ref(null);

const FeOptions = [
    { name: "Accounts", image: "/art/payments/accounts.png?v=2", value: "accounts" },
    { name: "Bank", image: "/art/payments/bank.png?v=2", value: "bank" },
    { name: "btree", image: "/art/payments/btree.png?v=2", value: "btree" },
    { name: "Cash", image: "/art/payments/cash.png?v=2", value: "Cash" },
    { name: "Checkout", image: "/art/payments/checkout.png?v=2", value: "Checkout" },
    { name: "Cond", image: "/art/payments/cond.png?v=2", value: "cond" },
    { name: "Hokodo", image: "/art/payments/hokodo.png?v=2", value: "hokodo" },
    { name: "Pastpay", image: "/art/payments/pastpay.png?v=2", value: "pastpay" },
    { name: "sofort", image: "/art/payments/sofort.png?v=2", value: "sofort" },
    { name: "Worldpay", image: "/art/payments/worldpay.png?v=2", value: "Worldpay" },
    { name: "Paypal", image: "/art/payments/paypal.png?v=2", value: "paypal" },
    { name: "Visa", image: "/art/payments/visa.png?v=2", value: "visa" },
    { name: "Paypal", image: "/art/payments/paypal-white.png?v=2", value: "paypal-white" },
    { name: "Pastpay", image: "/art/payments/pastpay-white.png?v=2", value: "Pastpay-white" },
    { name: "Mastercard", image: "/art/payments/mastercard-white.png?v=2", value: "mastercard-white" },
    { name: "Visa", image: "/art/payments/visa-white.png?v=2", value: "visa-white" },
];

const addPayments = (value: { name: string, image: string }) => {
    const data = cloneDeep(props.modelValue);
    data.push({ name: value.name, value: value.name, image: value.image });
    emits('update:modelValue', data);
    _addop.value.hide();
};

const updatePayment = (index: number, value: { name: string, image: string, value : string }) => {
    const data = cloneDeep(props.modelValue);
    data[index] = { name: value.name, value: value.name, image: value.image };
    emits('update:modelValue', data);
    _editop.value[index].hide();
};

const deleteSocial = (event: Event, index: number) => {
    event.stopPropagation();
    event.preventDefault();
    const data = cloneDeep(props.modelValue);
    data.splice(index, 1);
    emits('update:modelValue', data);
};

const toggleAdd = (event: Event) => {
    _addop.value?.toggle(event);
};

const toggleEdit = (event: Event, index : Number) => {
    console.log(_editop.value)
    _editop.value[index].toggle(event);
};
</script>

<template>
    <div class="p-4">
        <div v-for="(item, index) in modelValue" :key="index" class="p-2 flex justify-center">
            <div @click="(e)=>toggleEdit(e,index)" :style="{ backgroundColor: props.background || '#f9f9f9' }"
                class="relative flex flex-col items-center border border-gray-300 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 w-full max-w-xs p-4 m-2 transform hover:-translate-y-1">
                <!-- Delete Button -->
                <button @click="(e) => deleteSocial(e, index)"
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-sm text-xs  p-1 focus:outline-none shadow-md transition-all duration-200"
                    aria-label="Delete">
                    <FontAwesomeIcon :icon="['fas', 'trash']" />
                </button>

                <img v-if="item.image" class="h-auto max-h-7 md:max-h-8 max-w-full w-fit" :src="item.image"
                    alt="avatar" />
            </div>
            <Popover ref="_editop">
                <div class="grid grid-cols-5 gap-6">
                    <div v-for="icon in FeOptions" :key="icon.value" class="pr-4">
                        <div @click="() => updatePayment(index,icon)" :style="{ backgroundColor: props.background }"
                            class="flex flex-col items-center border-2 border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow w-full p-4 m-2">
                            <img class="w-12 h-12 rounded-full object-contain object-center mb-2 group-hover:opacity-75"
                                :src="icon.image" alt="avatar">
                            <div class="text-center truncate" style="max-width: 150px;">
                                {{ icon.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </Popover>
        </div>

        <Button type="dashed" icon="fal fa-plus" label="Add Payments Method" full size="s" class="mt-2" @click="toggleAdd" />
        <Popover ref="_addop">
            <div class="grid grid-cols-5 gap-6">
                <div v-for="icon in FeOptions" :key="icon.value" class="pr-4">
                    <div @click="() => addPayments(icon)" :style="{ backgroundColor: props.background }"
                        class="flex flex-col items-center border-2 border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow w-full p-4 m-2">
                        <img class="w-12 h-12 rounded-full object-contain object-center mb-2 group-hover:opacity-75"
                            :src="icon.image" alt="avatar">
                        <div class="text-center truncate" style="max-width: 150px;">
                            {{ icon.name }}
                        </div>
                    </div>
                </div>
            </div>
        </Popover>
    </div>
</template>

<style scoped></style>
