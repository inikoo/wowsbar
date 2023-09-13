<script setup lang="ts">
import { router } from '@inertiajs/vue3'

import UnderMaintenance from '@/Components/Utils/UnderMaintenance.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faRocketLaunch } from '@/../private/pro-solid-svg-icons'
import { faDraftingCompass } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faRocketLaunch, faDraftingCompass)

const props = defineProps<{
    data: any
}>()

const handleLaunch = () => {
    router.patch(
        route('org.models.website.state.update'),
        { status: 'live'},
    )
}
</script>

<template>
    <div class="px-6 py-24 sm:py-20 lg:px-8">
        <UnderMaintenance>
            <template #button>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <Button :style="`secondary`" @click="router.visit(route('org.website.workshop'))">
                        <div class="flex items-center gap-x-1">
                            <FontAwesomeIcon icon='fal fa-drafting-compass' class='' aria-hidden='true' />
                            Workshop
                        </div>
                    </Button>
                    <Button :style="`primary`" @click="handleLaunch">
                        <div class="flex items-center gap-x-1">
                            Launch
                            <FontAwesomeIcon icon='fas fa-rocket-launch' class='' aria-hidden='true' />
                        </div>
                    </Button>
                </div>
            </template>
        </UnderMaintenance>    
    </div>
</template>

<style scoped></style>
