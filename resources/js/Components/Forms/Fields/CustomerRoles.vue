<script setup lang="ts">
import { reactive, watch, ref, watchEffect } from 'vue'

const props = defineProps<{
    form?: any
    fieldName: any
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
		searchable?: boolean
    }
}>()

const realValue = ref()

const optionsRoles1 = ref(
{
    label: 'Super admin',
    name: 'superadmin',
    value: false,
    disabled: false
})

const optionsRoles2 = ref(
    {
        label: 'Portfolio',
        name: 'portfolio',
        value: false,
        disabled: false
    }
)

const optionsRoles3 = reactive([
    {
        label: 'Banners',
        name: 'banners',
        value: false,
        disabled: false
    },
    {
        label: 'Social',
        name: 'social',
        value: false,
        disabled: false
    },
    {
        label: 'SEO',
        name: 'seo',
        value: false,
        disabled: false
    },
    {
        label: 'Google-Ads',
        name: 'googleads',
        value: false,
        disabled: false
    },
    {
        label: 'Prospects',
        name: 'prospects',
        value: false,
        disabled: false
    }
])

watch(() => optionsRoles1.value.value, () => {
    optionsRoles1.value.value ? (optionsRoles2.value.value = true, realValue.value = optionsRoles1.value.name) : realValue.value = optionsRoles2.value.name
})

watch(() => optionsRoles2.value.value, () => {
    optionsRoles2.value.value ? (optionsRoles3.map(item => item.value = true), realValue.value = optionsRoles2.value.name) : realValue.value = optionsRoles3.filter(item => item.value === true).map(item => item.name)
})

watchEffect(() => {
    props.form[props.fieldName] = optionsRoles1.value.value
        ? optionsRoles1.value.name
        : optionsRoles2.value.value
            ? optionsRoles2.value.name
            : optionsRoles3.filter(item => item.value === true).map(item => item.name)
})
</script>

<template>
    <div>
        <tbody class="divide-y divide-gray-200">
            <tr>
                <td class="">
                    <label :for="optionsRoles1.name"
                        class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium cursor-pointer"
                        :class="[optionsRoles1.disabled ? 'text-gray-300' : 'text-gray-500 hover:text-gray-600']"
                    >
                        {{ optionsRoles1.label }}
                    </label>
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="optionsRoles1.value" :id="optionsRoles1.name"
                        :name="optionsRoles1.name" type="checkbox"
                        :titles="`I'm Interested in ${optionsRoles1.label}`"
                        :disabled="optionsRoles1.disabled"
                        class="h-5 w-5 rounded cursor-pointer disabled:text-gray-300 border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600"
                    />
                </td>
            </tr>
            
            <tr>
                <td class="">
                    <label :for="optionsRoles2.name"
                        class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium cursor-pointer"
                        :class="[optionsRoles1.value ? 'text-gray-300' : 'text-gray-500 hover:text-gray-600']"
                    >
                        {{ optionsRoles2.label }}
                    </label>
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="optionsRoles2.value" :id="optionsRoles2.name"
                        :name="optionsRoles2.name" type="checkbox"
                        :titles="`I'm Interested in ${optionsRoles2.label}`"
                        :disabled="optionsRoles1.value"
                        class="h-5 w-5 rounded cursor-pointer disabled:text-gray-300 border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600"
                    />
                </td>
            </tr>

            <tr v-for="(option, index) in optionsRoles3" :key="index">
                <td class="">
                    <label :for="option.name"
                        class="whitespace-nowrap block py-2 pl-4 pr-3 text-sm font-medium cursor-pointer"
                        :class="[optionsRoles2.value ? 'text-gray-300' : 'text-gray-500 hover:text-gray-600']"
                    >
                        {{ option.label }}
                    </label>
                </td>
                <td class="whitespace-nowrap px-3 text-sm text-gray-500 text-center">
                    <input v-model="option.value" :id="option.name"
                        :name="option.name" type="checkbox"
                        :titles="`I'm Interested in ${option.label}`"
                        :disabled="optionsRoles2.value"
                        class="h-5 w-5 rounded cursor-pointer disabled:text-gray-300 border-gray-300 hover:border-gray-500 text-gray-600 focus:ring-gray-600"
                    />
                </td>
            </tr>
        </tbody>
    <!-- <pre>{{ form[fieldName] }}</pre> -->
    <!-- <pre>{{ realValue }}</pre> -->
    </div>
</template>