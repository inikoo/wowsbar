<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import Multiselect from "@vueform/multiselect"
import { cloneDeep } from 'lodash'
import axios from 'axios'

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

const FeOptions = [
    {
        name: "Accounts",
        image: "/art/accounts.png",
        value: "accounts",
    },
    {
        name: "Bank",
        image: "/art/bank.png",
        value: "bank",
    },
    {
        name: "btree",
        image: "/art/btree.png",
        value: "btree",
    },
    {
        name: "Cash",
        image: "/art/cash.png",
        value: "Cash",
    },
    {
        name: "Checkout",
        image: "/art/checkout.png",
        value: "Checkout",
    },
    {
        name: "Cond",
        image: "/art/cond.png",
        value: "cond",
    },
    {
        name: "Hokodo",
        image: "/art/hokodo.png",
        value: "hokodo",
    },
    {
        name: "Pastpay",
        image: "/art/pastpay.png",
        value: "pastpay",
    },
    {
        name: "sofort",
        image: "/art/sofort.png",
        value: "sofort",
    },
    {
        name: "Worldpay",
        image: "/art/worldpay.png",
        value: "Worldpay",
    },
    {
        name: "Paypal",
        image: "/art/paypal.png",
        value: "paypal",
    },
    {
        name: "Mastercard",
        image: "/art/mastercard.png",
        value: "mastercard",
    },
    {
        name: "Visa",
        image: "/art/visa.png",
        value: "visa",
    },
]

const GetPayment = async () => {
    try {
        const response = await axios.get(
            route('customer.accounting.payment-service-providers.index'),
        )
         if (response && response.data && response.data.data) {
             const ini = response.data.data.map((item) => ({
                 name: item.name,
                 value: item.name,
                 image: item.logo
             }))
             payments.value = ini
         } else {
             console.error('Invalid response format', response)
         }
    } catch (error: any) {
        console.error('error', error)
    }
}

const payments = ref([])


const addPayments = () => {
    let data = cloneDeep(props.modelValue.data);
    data.push(
        {
            name: "checkout",
            value: "checkout",
            image: "https://www.linqto.com/wp-content/uploads/2023/04/logo_2021-11-05_19-04-11.530.png",
        },
    );
    emits('update:modelValue', { data: data });
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

onMounted(() => {
    GetPayment()
});


</script>

<template>
    <div class="p-4">
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
        <Button type="dashed" icon="fal fa-plus" label="Add Payments Method" full size="s" class="mt-2"
            @click="addPayments" />
    </div>
</template>

<style scoped></style>
