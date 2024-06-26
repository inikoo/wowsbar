<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 03:12:13 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import { useForm } from '@inertiajs/vue3'
import { useLayoutStore } from '@/Stores/layout'
import { routeType } from '@/types/route'
import { ref, computed } from 'vue'
import type { Component } from 'vue'
import axios from 'axios'

import Input from '@/Components/Forms/Fields/Input.vue'
import SenderEmail from '@/Components/Forms/Fields/SenderEmail.vue'
import Phone from '@/Components/Forms/Fields/Phone.vue'
import Date from '@/Components/Forms/Fields/Date.vue'
import Theme from '@/Components/Forms/Fields/Theme.vue'
import ColorMode from '@/Components/Forms/Fields/ColorMode.vue'
import Avatar from '@/Components/Forms/Fields/Avatar.vue'
import Logo from '@/Components/Forms/Fields/Logo.vue'
import Password from '@/Components/Forms/Fields/Password.vue'
import Textarea from '@/Components/Forms/Fields/Textarea.vue'
import FieldToggle from '@/Components/Forms/Fields/FieldToggle.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Radio from '@/Components/Forms/Fields/Radio.vue'
import TextEditor from '@/Components/Forms/Fields/TextEditor.vue'
import Address from "@/Components/Forms/Fields/Address.vue"
import Country from "@/Components/Forms/Fields/Country.vue"
import Currency from "@/Components/Forms/Fields/Currency.vue"
import Language from "@/Components/Forms/Fields/Language.vue"
import Permissions from "@/Components/Forms/Fields/Permissions.vue"
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import Checkbox from '@/Components/Forms/Fields/Checkbox.vue'
import ToggleSquare from '@/Components/Forms/Fields/ToggleSquare.vue'
import CustomerRoles from '@/Components/Forms/Fields/CustomerRoles.vue'
import JobPosition from '@/Components/Forms/Fields/JobPosition.vue'
import ProspectRecipients from '@/Components/Forms/Fields/ProspectRecipients.vue'
import ProspectsQuery from '@/Components/Forms/Fields/ProspectQuery/ProspectQueryBuilder.vue'
import AppLogin from '@/Components/Forms/Fields/AppLogin.vue'


import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSave as fadSave, } from '@fad'
import { faSave as falSave, faInfoCircle } from '@fal'
import { faAsterisk, faQuestion } from '@fas'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(fadSave, faQuestion, falSave, faInfoCircle, faAsterisk)

const props = defineProps<{
    field: any
    fieldData: {
        type: string
        label: string
        verification?: {
            route: routeType
        }
        value: any
        mode?: string
        required?: boolean
        options?: {}[]
        full: boolean
        noTitle?: boolean
        noSaveButton?: boolean  // Button: save
    }
    args: {
        updateRoute: routeType
    }
}>()

const layout = useLayoutStore()
const updateRoute = props['fieldData']['updateRoute'] ?? props.args['updateRoute'];

const components: {[key: string]: Component} = {
    'select': Select,
    'input': Input,
    'inputWithAddOn': InputWithAddOn,
    'phone': Phone,
    'date': Date,
    'theme': Theme,
    'colorMode': ColorMode,
    'password': Password,
    'avatar': Avatar,
    'logo': Logo,
    'textarea': Textarea,
    'toggle': FieldToggle,
    'radio': Radio,
    'textEditor': TextEditor,
    'address': Address,
    'country': Country,
    'currency': Currency,
    'language': Language,
    'permissions': Permissions,
    'toggleSquare': ToggleSquare,
    'checkbox': Checkbox,
    'customerRoles': CustomerRoles,
    'jobPosition': JobPosition,
    'prospect_query': ProspectsQuery,
    'ProspectRecipients': ProspectRecipients,
    'senderEmail': SenderEmail,
    'app_login': AppLogin,
}

const getComponent = (componentName: string) => {
    return components[componentName] ?? null;
};

let formFields = {
    [props.field]: props.fieldData.value,
};

if (props['fieldData']['hasOther']) {
    formFields[props['fieldData']['hasOther']['name']] = props['fieldData']['hasOther']['value'];
}
formFields['_method'] = 'patch'
const form = useForm(formFields)
form['fieldType'] = 'edit'

function submit() {
    form.post(route(updateRoute.name, updateRoute.parameters), {
        preserveScroll: true,
    })
}


const classVerification = ref('')
const isVerificationLoading = ref(false)
const labelVerification = ref('')
const stampDirtyValue = ref(props.fieldData.value ?? '')

const isVerificationDirty = computed(() => {
    return (stampDirtyValue.value !== form[props.field])
})

const checkVerification = async () => {
    isVerificationLoading.value = true
    try {
        const response = await axios.post(
            route(
                props.fieldData.verification?.route.name,
                props.fieldData.verification?.route.parameters
            ),
            { [props.field]: form[props.field] },
        )
        labelVerification.value = response.data
        classVerification.value = 'text-lime-500'

    }
    catch (error: any) {
        labelVerification.value = error.response.data.message
        classVerification.value = 'text-red-500'
    }
    isVerificationLoading.value = false

    stampDirtyValue.value = form[props.field]
}
</script>

<template>
    <form @submit.prevent="submit" class="divide-y divide-gray-200 w-full" :class="props.fieldData.full ? '' : 'max-w-2xl'">
        <dl class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 ">
            <!-- Title -->
            <dt v-if="!fieldData.noTitle" class="text-sm font-medium text-gray-400 capitalize">
                <div class="inline-flex items-start leading-none"><FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']" class="font-light text-[12px] text-red-400 mr-1"/>{{ fieldData.label }}</div>
            </dt>

            <dd :class="props.fieldData.full ? 'sm:col-span-3' : fieldData.noTitle ? 'sm:col-span-3' : 'sm:col-span-2'">
                <div class="mt-1 flex items-start text-sm text-gray-700 sm:mt-0">
                    <div class="relative flex-grow">
                        <component :is="getComponent(fieldData.type)" :form="form" :fieldName="field"
                            :options="fieldData.options" :fieldData="fieldData">
                        </component>

                        <!-- Verification: Label -->
                        <div v-if="labelVerification" class="mt-1" :class="classVerification">
                            <FontAwesomeIcon icon='fal fa-info-circle' class='opacity-80' aria-hidden='true' />
                            <span class="ml-1 font-medium">{{ labelVerification }}</span>
                        </div>
                    </div>

                    <!-- Button: Save -->
                    <template v-if="fieldData.noSaveButton" />
                    
                    <span v-else class="ml-2 flex-shrink-0">
                        <button v-if="!fieldData.verification" class="align-bottom" :disabled="form.processing || !form.isDirty" type="submit">
                            <FontAwesomeIcon v-if="form.isDirty" icon="fad fa-save" class="h-8 text-org-600"
                                :style="{
                                    '--fa-secondary-color': [layout.systemName === 'org' ? 'rgb(69, 38, 80)' : 'rgb(0, 255, 4)']
                                }" aria-hidden="true" />
                            <FontAwesomeIcon v-else icon="fal fa-save" class="h-8 text-gray-300" aria-hidden="true" />
                        </button>

                        <!-- Verification: Button -->
                        <span v-else>
                            <FontAwesomeIcon v-if="isVerificationLoading" icon='fad fa-spinner-third' class='animate-spin h-8 text-gray-500 hover:text-gray-600 cursor-pointer' aria-hidden='true' />
                            <FontAwesomeIcon v-else @click="isVerificationDirty ? checkVerification() : ''"
                                icon='fas fa-question'
                                class='h-8'
                                :class="isVerificationDirty ? 'text-gray-500 hover:text-gray-600 cursor-pointer' : 'text-gray-300'"
                                aria-hidden='true' />
                        </span>
                    </span>
                </div>
            </dd>
        </dl>
    </form>
</template>
