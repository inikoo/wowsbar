<script setup lang="ts">
import PureInputWithAddOn from '@/Components/Pure/PureInputWithAddOn.vue'
import { ref } from 'vue'

const abc = ref('aaa')
</script>

<template>
    <div class="mx-auto w-96">
        <label for="inputWebsite">Enter your website</label>
        <PureInputWithAddOn
            v-model="abc"
            :leftAddOn="{ label: 'https://' }"
            placeholder="Enter your website url"
            :rightAddOn="{ label: '.com' }"
            inputName="inputWebsite"
        />
    </div>
</template>