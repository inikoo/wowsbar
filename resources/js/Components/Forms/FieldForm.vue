<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 03:12:13 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import { useForm } from '@inertiajs/vue3'
import { useLayoutStore } from '@/Stores/layout'
import { routeType } from '@/types/route'

import Input from '@/Components/Forms/Fields/Input.vue'
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
import ProspectRecipients from '@/Components/Forms/Fields/prospectRecipients.vue'
import ProspectsQuery from '@/Components/Forms/Fields/ProspectQuery/ProspectQueryBuilder.vue'


import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSave as fadSave } from '@fad/'
import { faSave as falSave } from '@fal/'
import { faAsterisk } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(fadSave, falSave, faAsterisk)

const props = defineProps<{
    field: any
    fieldData: {
        type: string
        label: string
        value: any
        mode?: string
        required?: boolean
        fullComponentArea?: boolean
        options?: {}[]
    }
    args: {
        updateRoute: routeType
    }
}>()

const layout = useLayoutStore()
const updateRoute = props['fieldData']['updateRoute'] ?? props.args['updateRoute'];

const components = {
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
};

const getComponent = (componentName) => {
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
    form.post(route(updateRoute.name, updateRoute.parameters))
}
</script>

<template>
    <form @submit.prevent="submit">
        <dl v-if="!props.fieldData.fullComponentArea" class="divide-y divide-gray-200" :class="props.fieldData.full ? '' : 'max-w-2xl'">
            <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 ">
                <dt class="text-sm font-medium text-gray-400 capitalize">
                    <div class="inline-flex items-start leading-none"><FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']" class="font-light text-[12px] text-red-400 mr-1"/>{{ fieldData.label }}</div>
                </dt>
                <dd :class="props.fieldData.full ? 'sm:col-span-3' : 'sm:col-span-2'">
                    <div class="mt-1 flex items-start text-sm text-gray-900 sm:mt-0">
                        <div class="relative  flex-grow">
                            <component :is="getComponent(fieldData['type'])" :form=form :fieldName=field
                                :options="fieldData['options']" :fieldData="fieldData">
                            </component>
                        </div>

                        <!-- Button: Save -->
                        <span class="ml-2 flex-shrink-0">
                            <button class="align-bottom" :disabled="form.processing || !form.isDirty" type="submit">
                                <FontAwesomeIcon v-if="form.isDirty" icon="fad fa-save" class="h-8 text-org-600"
                                    :style="{
                                        '--fa-secondary-color': [layout.systemName === 'org' ? 'rgb(69, 38, 80)' : 'rgb(0, 255, 4)']
                                    }" aria-hidden="true" />
                                <FontAwesomeIcon v-else icon="fal fa-save" class="h-8 text-gray-300" aria-hidden="true" />
                            </button>
                        </span>
                    </div>
                </dd>
            </div>
        </dl>

        <!-- full area components -->
        <dl v-if="props.fieldData.fullComponentArea" class="divide-y divide-gray-200">
                <dd class="sm:col-span-2  ">
                    <div class="mt-1 flex items-start text-sm text-gray-900 sm:mt-0">
                        <div class="relative  flex-grow">
                            <component :is="getComponent(fieldData['type'])" :form=form :fieldName=field
                                :options="fieldData['options']" :fieldData="fieldData">
                            </component>
                        </div>
                    </div>
                </dd>
        </dl>

    </form>
</template>
