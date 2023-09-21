<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 16:55:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { Link } from "@inertiajs/vue3"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import {
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo, faBrowser,
    faFolderTree,faCube

} from "@/../private/pro-light-svg-icons"
import { useLayoutStore } from "@/Stores/layout"
import { capitalize } from "@/Composables/capitalize"
import { get } from 'lodash'

library.add(
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo,
    faBrowser,faFolderTree,faCube
)

const layout = useLayoutStore()


</script>

<template>

</template>
