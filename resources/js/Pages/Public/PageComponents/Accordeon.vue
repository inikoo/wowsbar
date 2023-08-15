<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
  <script setup lang="ts">
  import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
  } from '@headlessui/vue'
  import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'
  const props = defineProps<{
    data: Object
  }>()
  
  </script>
    
  <template>
        <div class="divide-y divide-gray-200 border-t p-2.5">
          <Disclosure as="div" v-for="detail in data.details" :key="detail.name" v-slot="{ open }">
            <h3>
              <DisclosureButton class="group relative flex w-full items-center justify-between py-6 text-left">
                <span :class="[open ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-medium']">{{ detail.name }}</span>
                <span class="ml-6 flex items-center">
                  <PlusIcon v-if="!open" class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                  <MinusIcon v-else class="block h-6 w-6 text-indigo-400 group-hover:text-indigo-500" aria-hidden="true" />
                </span>
              </DisclosureButton>
            </h3>
            <DisclosurePanel as="div" class="prose prose-sm pb-6">
              <ul role="list">
                <li v-for="data in detail.items" :key="data">{{ data }}</li>
              </ul>
            </DisclosurePanel>
          </Disclosure>
        </div>
  </template>
    
    
    