<script  setup lang="ts">
import PureRadio from '@/Components/Pure/PureRadio.vue'
import { BannerWorkshop } from '@/types/BannerWorkshop'

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
        sideNav: {
            value: true,
            type: 'arrow'
        },
        bottomNav: {
            value: true,
            type: 'bullet'  // button
        }
    }
}

const bottomNavOptions = [
    {
        value: 'bullet'
    },
    {
        value: 'button'
    },
]

</script>

<template>
    <div>
        <tbody class="divide-y divide-gray-200">
            <tr v-for="(option, index) in fieldData?.options" :key="index">
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center flex py-1.5">
                    <input 
                        v-model="data.navigation[option.name].value"
                        :id="`item-${index}`" :name="`item-${index}`" type="checkbox"
                        :titles="`I'm Interested in ${option.label}`"
                        class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600" />
                </td>
                <td class="">
                    <label :for="`item-${index}`"
                        class="whitespace-nowrap block py-2 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600 cursor-pointer">
                        {{ option.label }}
                    </label>
                    <PureRadio v-if="data.navigation?.[option.name].value && option.name == 'bottomNav'" v-model="data.navigation[option.name].type" :options="bottomNavOptions" />
                </td>
            </tr>
        </tbody>
    </div>
</template>