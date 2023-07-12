<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 15 May 2023 13:12:47 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { capitalize } from "@/Composables/capitalize"

const props = defineProps<{
    data: string[]
}>()

const countryCode= props.data ? props.data[0] : null
const countryName=props.data ? props.data[1] : null
const addressLocation=props.data ? props.data[2] : null

let flag=null;
if(countryCode){
    flag= '/flags/'+countryCode.toLowerCase()+'.png'  ;
}


</script>

<template>
    <img v-if="flag" class="inline pr-1" :src='flag' :alt="countryCode"   :title='capitalize(countryName)'  /> {{addressLocation}}
</template>


