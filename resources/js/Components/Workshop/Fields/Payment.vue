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

const GetPayment = async () => {
    try {
        const response = await axios.get(
            route('customer.accounting.payment-service-providers.index'),
        )
        console.log(response)
        /*  if (response && response.data && response.data.data) {
             const ini = response.data.data.map((item) => ({
                 name: item.name,
                 value: item.name,
                 image: item.logo
             }))
             payments.value = ini
         } else {
             console.error('Invalid response format', response)
         } */
    } catch (error: any) {
        console.error('error', error)
    }
}

const payments = ref(
    [
        {
            name: "Account",
            value: "Account",
            image: "http://10.0.0.100:8080/X38TpeGGthplkff_9H-a4bAf0NIoLsKTCoqVb1pkJR4/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyNC9iODEwYzZlN2NlYjExM2E3Nzk2Njg4YTUyMzk0NWZkNi5pbWFnZS1wbmc"
        },
        {
            name: "Bank",
            value: "Bank",
            image: "http://10.0.0.100:8080/ktrvH060gisaofNEU85FNSuLaYqWmwk8tRec-pYbMww/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyNi9iZTA3MDdmOTEyNTk0MTI2ZDc4ZmEzMmU2MWY1MjM2YS5pbWFnZS1wbmc"
        },
        {
            name: "Btree",
            value: "Btree",
            image: "http://10.0.0.100:8080/Ag3VzsEHFmapcOyvG-vePxRQRkFttWyA00Q7cYiN_70/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyNy9iMzI3NzIzMjAxNGJmZmExYmM4NWFhODVhOTBjOTdkNi5pbWFnZS1wbmc"
        },
        {
            name: "Cash",
            value: "Cash",
            image: "http://10.0.0.100:8080/YSzJb88aKuDiGAuwwx6-tugTFJfZmZnPlAjgLo4AwV0/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyNS80MmQ4YjNiMTM0MGZlYmQ4YmE1YjJmNWJhYWI0NzcwYi5pbWFnZS1wbmc"
        },
        {
            name: "Checkout",
            value: "Checkout",
            image: "http://10.0.0.100:8080/RwDUfiomQukTP-H6oJOC-iTAd6YfHJpkKbvP8ILurgw/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyOC80NzdhMGIxYjU0NzhmZGY2ZjZiNDMwMTc1MGQ4Y2NmOC5pbWFnZS1wbmc"
        },
        {
            name: "Cash on delivery",
            value: "Cash on delivery",
            image: "http://10.0.0.100:8080/OK-pau4xjRX34Ndjtiq2cIecAJdF8LNSowib3kfD3tU/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzNS9lODY2Mzg4ZjI3OTBmNjUzMzIxNWZiOTVkZjBmZmIwYi5pbWFnZS1wbmc"
        },
        {
            name: "Hokodo",
            value: "Hokodo",
            image: "http://10.0.0.100:8080/S_eltHQT0QdWrsPPp3gU9PpJiWiRDiRrYFvl_ljGpH4/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMyOS80Mzg2ZDA0Yjg3ZDUxM2ZlMjIwNWY1ZTFlODQ1OGU0MC5pbWFnZS1wbmc"
        },
        {
            name: "Pastpay",
            value: "Pastpay",
            image: "http://10.0.0.100:8080/VOmcarVa45Xc1wBzA0zOoXtEfC4NXT0myGA7YPI2FFo/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzMi8wMzBlZDI1Yzk1NzkyNjQxNzBmNDJlOGI0ZmI2M2UwZS5pbWFnZS1wbmc"
        },
        {
            name: "Paypal",
            value: "Paypal",
            image: "http://10.0.0.100:8080/GRbbdcntDve-rTXAy13_CJk6kzL7fKwaBn6uEXMB89g/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzMC85ZmI4NzU0YjYzOWQ5NGViMGU4YmMzYjY1M2FlYThkNC5pbWFnZS1wbmc"
        },
        {
            name: "Sofort",
            value: "Sofort",
            image: "http://10.0.0.100:8080/Ie6XR9dzAajd9U_Hi66thiyIjYLuIAAmafRDzzeOFfo/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzMS9mMjMzMTA0YjI2MTgxNWYwNmZjOGY2ZTA2MzRkYzQ5NS5pbWFnZS1wbmc"
        },
        {
            name: "Worldpay",
            value: "Worldpay",
            image: "http://10.0.0.100:8080/7Ii0FXxaQR6vOkcZyO8MbHvCvL_Zu01euYD_4CgZ7yM/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzNC9kOGYzYzk1OGM5MDU5MjBiMWZhYjM3Y2RhMjhkMDJmYS5pbWFnZS1wbmc"
        },
        {
            name: "Xendit",
            value: "Xendit",
            image: "http://10.0.0.100:8080/QvUYWR35xXY6Gw6w3WaG_QJQFstzEs7QLRIGE1RU_2g/bG9jYWw6Ly8vYWlrdS9hcHAvbWVkaWEvbWVkaWEvMjMzMy8zYTg3ZGEwMGE3ODNhZWQxNzZhNjlhNzc2MDEwMDI0My5pbWFnZS1wbmc"
        }
    ]
)


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
                <Multiselect :modelValue="item" :options="payments" :object="true" :canClear="false" :caret="false"
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
