<script setup>

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import { faExclamationCircle ,faCheckCircle} from '@fas/';
import {library} from '@fortawesome/fontawesome-svg-core';
import PureTextarea from '@/Components/Pure/PureTextarea.vue';
library.add(faExclamationCircle,faCheckCircle);

const props = defineProps(['form', 'fieldName','options', 'fieldData']);

const handleChange = (form) => {
    if(form.fieldType==='edit'){
        form.clearErrors();
    }
}

let type='text'
if(props.options!==undefined && props.options.type ){
    type=props.options.type;
}
</script>


<template>
    <div class="relative">
        <div>
            <label :for="fieldName" class="block text-sm font-medium text-gray-700"></label>
            <PureTextarea
                v-model="form[fieldName]"
                :placeholder="fieldData.placeholder"
                :readonly="fieldData.readonly"
                :inputName="fieldName"
                :counter="fieldData.counter"
            >

            </PureTextarea>
        </div>

        <!-- Icon: Error, Success, Loading -->
        <div class="absolute top-2 right-0 pr-3 flex items-center pointer-events-none">
            <FontAwesomeIcon v-if="form.errors[fieldName]" icon="fas fa-exclamation-circle" class="h-5 w-5 text-red-500" aria-hidden="true" />
            <FontAwesomeIcon v-if="form.recentlySuccessful" icon="fas fa-check-circle" class="h-5 w-5 text-green-500" aria-hidden="true" />
            <FontAwesomeIcon v-if="form.processing" icon="fad fa-spinner-third" class="h-5 w-5 animate-spin dark:text-gray-200" />
        </div>
    </div>
    <p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600" id="email-error">{{ form.errors[fieldName] }}</p>
</template>


