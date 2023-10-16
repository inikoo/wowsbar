<script setup>
import Hyperlink from '@/Components/CMS/Fields/Hyperlink.vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faEnvelope as fasEnvelope, faPhone as fasPhone, faBuilding as fasBuilding, faCircle as fasCircle, faMap as fasMap, faUser as fasUser } from '@fas/';
import { faEnvelope as falEnvelope, faPhone as falPhone, faBuilding as falBuilding, faCircle as falCircle, faMap as falMap, faUser as falUser } from '@fal/';
import { faEnvelope as farEnvelope, faPhone as farPhone, faBuilding as farBuilding, faCircle as farCircle, faMap as farMap, faUser as farUser } from '@far/';
import { faEnvelope as fadEnvelope, faPhone as fadPhone, faBuilding as fadBuilding, faCircle as fadCircle, faMap as fadMap, faUser as fadUser } from '@fad/';
import { faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram, faWhatsapp, faSquareWhatsapp } from "@fortawesome/free-brands-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import { upperFirst } from 'lodash'
import Button from "@/Components/Elements/Buttons/Button.vue"
import IconPicker from '@/Components/CMS/Fields/IconPicker/IconPicker.vue';
library.add(
  faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram, faWhatsapp, faSquareWhatsapp,
  fasEnvelope, fasPhone, fasBuilding, fasCircle, fasMap, fasUser,
  falEnvelope, falPhone, falBuilding, falCircle, falMap, falUser,
  farEnvelope, farPhone, farBuilding, farCircle, farMap, farUser,
  fadEnvelope, fadPhone, fadBuilding, fadCircle, fadMap, fadUser,
)

const props = defineProps({
  cssClass: String,
  data: {
    icon: String
  }
})
const emits = defineEmits();
</script>


<template>
  <Hyperlink :formList="{
    label: 'label',
    link: 'link',
  }" :useDelete="false" :data="data" label="label"
    cssClass="space-y-3 text-sm leading-6 text-gray-600 hover:text-indigo-500">
    <template #label>
      <FontAwesomeIcon :icon="props.data.icon"  aria-hidden="true" />
    </template>
    <template #content="{ onRef: contentRef }">
      <div class="flex justify-center text-[45px]">
        <IconPicker :data="props.data" />
      </div>
      <div v-for="item of contentRef.formList" class="m-1">
        <span class="text-sm">{{ upperFirst(item) }}</span>
        <input v-model="contentRef.value[item]" class="w-full" @blur="() => contentRef.handleInputBlur(item)" />
      </div>
      <div  class="my-2 mx-1">
						<Button @click="emits('OnDelete')" class="w-full flex justify-center bg-red-500">
							Delete
						</Button>
					</div>
    </template>
  </Hyperlink>
</template>




