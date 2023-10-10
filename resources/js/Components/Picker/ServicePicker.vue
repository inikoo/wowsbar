<script setup lang="ts">
import axios from 'axios'

const props = defineProps<{
    data: {
        id: number
        slug: string
        code: string
        name: string
        state?: any
        interest: string
        description?: string
        created_at: string
        updated_at: string
    }[]
    routeToUpdate: string
}>()

// Change interest: if null to not_sure
props.data.map((item: any) => { if(item.interest == null) { item.interest = 'not_sure' }})

// When the radio is updated
const handleRadioChanged = async (itemValue: string, itemSlug: string) => {
    try {
        await axios.patch(
            route(props.routeToUpdate, itemSlug),
            {
                interest: itemValue
            }
        )
    } catch (error: any) {
        console.error(error.message)
    }
}
</script>

<template>
    <table class="min-w-full divide-y divide-gray-300">
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
            <tr v-for="item in props.data" :key="item.id" class="hover:bg-gray-100">
                <td class="">
                    <div
                        class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600">
                        {{ item.name }}
                    </div>
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="item.interest" value="interested" :id="`item-${item.id}`"
                        @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio"
                        :title="`I'm Interested in ${item.name}.`"
                        class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-lime-500 text-lime-500 focus:ring-lime-600" />
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="item.interest" value="not_interested" :id="`item-${item.id}`"
                        @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio"
                        :title="`I'm not interested in ${item.name}.`"
                        class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-rose-500 text-rose-500 focus:ring-rose-600" />
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="item.interest" value="not_sure" :id="`item-${item.id}`"
                        @change="() => handleRadioChanged(item.interest, item.slug)" :name="`item-${item.id}`" type="radio"
                        :title="`I'm not sure.`"
                        class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-400 focus:ring-gray-600" />
                </td>
            </tr>
        </tbody>
    </table>
</template>