<script setup lang="ts">
import ServicePicker from '@/Components/Picker/ServicePicker.vue'

const props = defineProps<{
    data: any
}>()




</script>

<template>{{ data }}
    <div class="max-w-xl px-4 sm:px-6 lg:px-8 pt-4">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <ServicePicker
                    :data="data.basket.data"
                    :routeToUpdate="{
                        name: 'org.models.interest.store',
                        parameters: route().params.customerWebsite
                    }"
                />
                <!-- <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr class="text-gray-600 bg-gray-200">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold tracking-wide">
                                Name
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer">
                                Interested
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer">
                                Not interested
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center cursor-pointer">
                                Not sure
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="item in data.basket.data" :key="item.email" class="hover:bg-gray-100">
                            <td class="">
                                <div class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600">
                                    {{ item.name }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.interest" value="interested" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio" :title="`I'm Interested in ${item.name}.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-lime-500 text-lime-500 focus:ring-lime-600" />
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.interest" value="not_interested" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio" :title="`I'm not interested in ${item.name}.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-rose-500 text-rose-500 focus:ring-rose-600" />
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.interest" value="not_sure" :id="`item-${item.id}`" @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio" :title="`I'm not sure.`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-400 focus:ring-gray-600" />
                            </td>
                        </tr>
                    </tbody>
                </table> -->
            </div>
        </div>
    </div>
</template>
