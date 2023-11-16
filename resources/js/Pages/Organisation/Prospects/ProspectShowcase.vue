<script setup lang='ts'>
import { useLayoutStore } from '@/Stores/layout'
import { trans } from 'laravel-vue-i18n'
import Image from '@/Components/Image.vue'
import { useCopyText } from '@/Composables/useCopyText'
import { useFormatTime } from '@/Composables/useFormatTime'
import ContactCard from '@/Components/DataDisplay/ContactCard.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPhone, faEnvelope, faGlobe, faUser } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
import Timeline from '@/Components/Utils/Timeline.vue'
library.add(faPhone, faEnvelope, faGlobe, faUser)

const props = defineProps<{
    data: any
}>()

const timelineSort = props.data.timeline.sort((a, b) => {
    return a.value - b.value;
})

const timelineFilterNull = Object.groupBy(timelineSort.filter(obj => obj.value), (item) => {
    return item.value
})

const titlesObject = {};
for (const key in timelineFilterNull) {
  titlesObject[key] = timelineFilterNull[key].map(item => item.title);
}

const dataContact = {
    name: props.data.info.name,
    date: props.data.info.created_at,
    email: props.data.info.email,
    phone: props.data.info.phone,
    website: props.data.info.website,
    tags: props.data.info.tags
}
</script>

<template>
    <!-- <pre>{{ data.info }}</pre> -->
    <Timeline :options="titlesObject"/>

    <div class="px-4 py-4 space-y-8">
        <ContactCard :data="dataContact"/>
    </div>
</template>