<script setup lang="ts">
import axios from 'axios'

const props = defineProps<{
    data: any
}>()

// When the checkbox is updated
const handleRadioChanged = async (itemId: number, itemValue: string) => {
    // try {
    //     await axios.patch(
    //         route('xxx'),
    //         {
    //             id: itemId,
    //             value: itemValue
    //         },
    //         {
    //             headers: { "Content-Type": "multipart/form-data" },
    //         }
    //     )
    // } catch (error: any) {
    //     console.error(error.message)
    // }
    console.log(itemId, itemValue)
}
</script>

<template>
    <div class="max-w-xl px-4 sm:px-6 lg:px-8 pt-4">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr class="text-gray-600 bg-gray-200">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold tracking-wide">
                                Name
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer hover:text-lime-700" @click="() => data.basket.data.map((item: any) => item.state = 'interested')">
                                Interested
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer hover:text-rose-700" @click="() => data.basket.data.map((item: any) => item.state = 'not interested')">
                                Not interested
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer hover:text-gray-800" @click="() => data.basket.data.map((item: any) => item.state = 'not sure')">
                                Not sure
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="item in data.basket.data" :key="item.email">
                            <td class="">
                                <div class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600 cursor-pointer">
                                    {{ item.name }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.state" value="interested" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.id, item.state)" :name="`item-${item.id}`" type="radio" :title="`I'm Interested in ${item.name}.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-lime-500 text-lime-500 focus:ring-lime-600" />
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.state" value="not interested" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.id, item.state)" :name="`item-${item.id}`" type="radio" :title="`I'm not interested in ${item.name}.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-rose-500 text-rose-500 focus:ring-rose-600" />
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.state" value="not sure" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.id, item.state)" :name="`item-${item.id}`" type="radio" :title="`I'm not sure.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-400 focus:ring-gray-600" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>