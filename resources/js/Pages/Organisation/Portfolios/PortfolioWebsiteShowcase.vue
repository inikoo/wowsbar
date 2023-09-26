<script setup lang="ts">

const props = defineProps<{
    data: any
}>()

// When the checkbox is updated
const handleInputChanged = (id: number, val: string) => {
    console.log(props.data.basket.data[id-1])
}
</script>

<template>
    <div class="max-w-lg px-4 sm:px-6 lg:px-8 pt-4">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr class="text-gray-600 bg-gray-200">
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold tracking-wide">
                                Name
                            </th>
                            <th scope="col" class="px-3 py-3.5 font-semibold tracking-wide text-center">
                                Interested?
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="item in data.basket.data" :key="item.email">
                            <td class="">
                                <label :for="`item-${item.id}`" class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium text-gray-500 hover:text-gray-600 cursor-pointer">
                                    {{ item.name }}
                                </label>
                            </td>
                            <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                                <input v-model="item.state" :id="`item-${item.id}`" @change="() => handleInputChanged(item.id, item.state)" :name="`item-${item.id}`" type="checkbox" :titles="`I'm Interested in ${item.name}`"
                                    class="h-6 w-6 rounded cursor-pointer border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped></style>