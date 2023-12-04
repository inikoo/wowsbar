<script  setup lang="ts">
import { BannerWorkshop } from '@/types/BannerWorkshop'
import { onMounted } from 'vue';

const props = defineProps<{
    fieldName?: string | []
    fieldData?: {
        options: {
            label: string
            name: string
        }[]
    }
    data: BannerWorkshop
}>()

if(!props.data.navigation){
    props.data.navigation = {
        arrowNav: true,
        bulletNav: true
    }
}

</script>

<template>
    <div>
        <tbody class="divide-y divide-gray-200">
            <tr v-for="(option, index) in fieldData?.options" :key="index">
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input 
                        v-model="data.navigation[option.name]"
                        :id="`item-${index}`" :name="`item-${index}`" type="checkbox"
                        :titles="`I'm Interested in ${option.label}`"
                        class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600" />
                </td>
                <td class="">
                    <label :for="`item-${index}`"
                        class="whitespace-nowrap block py-2 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600 cursor-pointer">
                        {{ option.label }}
                    </label>
                </td>
            </tr>
        </tbody>
    </div>
</template>