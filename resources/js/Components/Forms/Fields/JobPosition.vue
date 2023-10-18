<script setup lang="ts">
import { watchEffect, ref, reactive, computed } from 'vue'
import PureRadio from '@/Components/Pure/PureRadio.vue'

const props = defineProps<{
    form?: any
    fieldName: any
    options: string[] | object
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
        searchable?: boolean
    }
}>()

interface selectedJob {
    admin?: string
    hr?: string
    acc?: string
    mrk?: string
    web?: string
    seo?: string
    ppc?: string
    social?: string
    deve?: string
    cus?: string
}

const optionsJob = {
    "admin": [
        {
            "code": "admin",
            "name": "Administrator",
            "department": "admin",
            "roles": ["super-admin"]
        }
    ],
    "hr": [
        {
            "code": "hr-m",
            "grade": "manager",
            "department": "admin",
            "name": "Human resources supervisor",
            "roles": ["human-resources-supervisor"]
        }, {
            "code": "hr-c",
            "name": "Human resources clerk",
            "department": "admin",
            "grade": "clerk",
            "roles": ["human-resources"]
        }
    ],

    "acc": [
        {
            "code": "acc-m",
            "department": "admin",
            "name": "Accounting manager",
            "roles": ["accounting-supervisor"]
        }, {
            "code": "acc-c",
            "department": "admin",
            "name": "Accounts",
            "roles": ["accounting"]
        }
    ],

    "mrk": [
        {
            "code": "mrk-m",
            "grade": "manager",
            "department": "marketing",
            "name": "Marketing supervisor",
            "roles": ["services-manager"]
        }, {
            "code": "mrk-c",
            "grade": "clerk",
            "department": "marketing",
            "name": "Marketing clerk",
            "roles": ["services"]
        }
    ],

    "web": [
        {
            "code": "web-m",
            "grade": "manager",
            "department": "marketing",
            "name": "Webmaster supervisor",
            "roles": ["web-master"]
        }, {
            "code": "web-c",
            "grade": "clerk",
            "department": "marketing",
            "name": "Webmaster clerk",
            "roles": ["web-editor"]
        }
    ],

    "seo": [
        {
            "code": "seo-m",
            "team": "seo",
            "department": "content",
            "name": "Seo supervisor",
            "roles": ["seo-supervisor"]
        }, {
            "code": "seo-w",
            "team": "seo",
            "department": "content",
            "name": "SEO",
            "roles": ["seo"]
        }
    ],

    "ppc": [
        {
            "code": "ppc-m",
            "team": "ppc",
            "department": "content",
            "name": "PPC supervisor",
            "roles": ["ppc-supervisor"]
        }, {
            "code": "ppc-w",
            "team": "ppc",
            "department": "content",
            "name": "PPC",
            "roles": ["ppc"]
        }
    ],

    "social": [
        {
            "code": "social-m",
            "team": "social",
            "department": "content",
            "name": "Social media supervisor",
            "roles": ["social-supervisor"]
        }, {
            "code": "social-w",
            "team": "social",
            "department": "content",
            "name": "Social media",
            "roles": ["social"]
        }
    ],

    "deve": [
        {
            "code": "dev-m",
            "team": "developer",
            "department": "development",
            "name": "Developer supervisor",
            "roles": ["caas-supervisor"]
        }, {
            "code": "dev-w",
            "team": "developer",
            "department": "development",
            "name": "Developer",
            "roles": ["caas"]
        }
    ],

    "cus": [
        {
            "code": "cus-m",
            "grade": "manager",
            "department": "customer-services",
            "name": "Customer service supervisor",
            "roles": ["customer-services-supervisor"]
        }, {
            "code": "cus-c",
            "grade": "clerk",
            "department": "customer-services",
            "name": "Customer service",
            "roles": ["customer-services"]
        }
    ]
}

// Temporary data
const selectedBox: selectedJob = reactive({
})

// When the box is clicked
const handleClickBox = (jobGroupName: string, job: any) => {
    if(selectedBox[jobGroupName as keyof selectedJob] == job) {  // When active box clicked
        selectedBox[jobGroupName as keyof selectedJob] = ''  // Deselect value
    } else {
        selectedBox[jobGroupName as keyof selectedJob] = job
    }
}

// To save the temporary data (selectedBox) to props.form
watchEffect(() => {
    const tempObject = {...selectedBox}
    selectedBox.admin ? '' : delete tempObject.admin
    props.form[props.fieldName] = selectedBox.admin ? [selectedBox.admin] : Object.values(tempObject)
})
</script>

<template>
    <div>
        <div class="flex gap-y-1 flex-col text-xs">
            <div v-for="(jobGroup, keyJob) in optionsJob" class="grid grid-cols-2 gap-x-1.5 gap-y-1">
                <!-- The box -->
                <button v-for="job in jobGroup"
                    @click.prevent="handleClickBox(keyJob, job.code)"
                    class="cursor-pointer active:ring-2 active:ring-gray-600 active:ring-offset-2 flex items-center justify-center rounded-md py-3 px-3 font-medium capitalize disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed disabled:ring-0 disabled:active:active:ring-offset-0"
                    :class="[
                        selectedBox[keyJob as keyof selectedJob] == job.code ? 'bg-gray-600 text-lime-300 hover:bg-gray-700' : 'ring-1 ring-inset ring-gray-300 hover:ring-2 hover:ring-gray-500 bg-white hover:bg-gray-200 text-gray-600 '
                    ]"
                    :disabled="selectedBox.admin && job.code != 'admin'? true : false"
                >
                        {{job.name}}
                </button>
            </div>
        </div>
    </div>
</template>