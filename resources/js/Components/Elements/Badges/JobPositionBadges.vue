<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 12 Sept 2022 15:58:38 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup>
import {Link} from '@inertiajs/vue3';

defineProps(['job_positions']);

const color = (position) => {
    switch (position) {
        case 'seo-w':
            return 'bg-pink-100 text-pink-800';

        default:
            return 'bg-gray-100 text-gray-800';
    }

};

</script>

<template>
    <Link v-for="(job_position, key) in job_positions" :key="key"
          :href="route('org.hr.job-positions.show',job_position.slug)"
          :title="job_position.name" :class="[color(job_position.slug),'inline-flex items-center rounded-full  px-3 py-0.5 text-sm font-medium  mr-2']">{{ job_position.slug }}
    </Link>
</template>


