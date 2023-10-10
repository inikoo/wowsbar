<script setup lang="ts">
import PureInputWithAddOn from '@/Components/Pure/PureInputWithAddOn.vue'
import { ref } from 'vue'

const abc = ref('aaa')
</script>

<template>
    <div class="mx-auto w-full">
        <label for="inputWebsite">Enter your website</label>
        <PureInputWithAddOn
            v-model="abc"
            :leftAddOn="{ label: 'https://' }"
            placeholder="Enter your website url"
            inputName="inputWebsite"
        />
    </div>
</template>