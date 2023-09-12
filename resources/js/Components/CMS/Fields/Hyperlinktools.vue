<script setup lang="ts">
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faEnvelope as fasEnvelope,
    faPhone as fasPhone,
    faBuilding as fasBuilding,
    faCircle as fasCircle,
    faMap as fasMap,
    faUser as fasUser,
} from "@/../private/pro-solid-svg-icons";
import {
    faEnvelope as falEnvelope,
    faPhone as falPhone,
    faBuilding as falBuilding,
    faCircle as falCircle,
    faMap as falMap,
    faUser as falUser,
} from "@/../private/pro-light-svg-icons";
import {
    faEnvelope as farEnvelope,
    faPhone as farPhone,
    faBuilding as farBuilding,
    faCircle as farCircle,
    faMap as farMap,
    faUser as farUser,
} from "@/../private/pro-regular-svg-icons";
import {
    faEnvelope as fadEnvelope,
    faPhone as fadPhone,
    faBuilding as fadBuilding,
    faCircle as fadCircle,
    faMap as fadMap,
    faUser as fadUser,
} from "@/../private/pro-duotone-svg-icons";
import {
    faTiktok,
    faFacebook,
    faFacebookF,
    faSquareFacebook,
    faInstagram,
    faSquareInstagram,
    faWhatsapp,
    faSquareWhatsapp,
} from "@fortawesome/free-brands-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
library.add(
    faTiktok,
    faFacebook,
    faFacebookF,
    faSquareFacebook,
    faInstagram,
    faSquareInstagram,
    faWhatsapp,
    faSquareWhatsapp,
    fasEnvelope,
    fasPhone,
    fasBuilding,
    fasCircle,
    fasMap,
    fasUser,
    falEnvelope,
    falPhone,
    falBuilding,
    falCircle,
    falMap,
    falUser,
    farEnvelope,
    farPhone,
    farBuilding,
    farCircle,
    farMap,
    farUser,
    fadEnvelope,
    fadPhone,
    fadBuilding,
    fadCircle,
    fadMap,
    fadUser
);
const props = defineProps<{
    data: Object;
    formList: Object;
}>();

const emits = defineEmits();

</script>

<template>
<div class="flex gap-2 justify-center align-middle">
    <div class="mt-2">
        <div v-for="item of formList">
            <div class="shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md rounded-md my-2">
                <input
                    v-model="data[item]"
                    type="text"
                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 w-full"
                    :placeholder="item"
                />
            </div>
        </div>
    </div>
    <div class="flex items-center">
        <button
            @click="emits('OnDelete')"
            class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm"
            style="margin-top: 2px;"
        >
            <font-awesome-icon :icon="['fas', 'trash']" />
        </button>
    </div>
</div>


</template>
