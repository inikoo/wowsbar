<script setup lang="ts">
import { ref, onMounted } from "vue";
import Dialog from "./Dialog.vue";
import PureInput from "@/Components/Pure/PureInput.vue";
import SelectButton from "primevue/selectbutton";

const props =
  defineProps<{
    show: boolean;
    currentUrl?: {
      class: string | null;
      href: string;
      rel: string;
      target: string;
    };
  }>();

const inputLinkRef = ref<string>(props.currentUrl?.href || "");
const target = ref<Object>(props.currentUrl?.target);

const emit = defineEmits(["close", "update"]);
const options = ref([
  { label: "Open in the same tab", value: "_self" },
  { label: "Open in a new tab", value: "_blank" }
]);

function closeDialog() {
  emit("close");
}

function update() {
  emit("update", inputLinkRef.value, target.value.value);
  emit("close");
}

onMounted(() => {
  inputLinkRef.value = props.currentUrl?.href ?? "";
  if(props.currentUrl?.target == "_self") target.value =  { label: "Open in the same tab", value: "_self" };
  else if(props.currentUrl?.target == "_blank") target.value = { label: "Open in a new tab", value: "_blank" }
  else target.value =  { label: "Open in the same tab", value: "_self" };
});
</script>

<template>
  <Dialog title="Link" :show="props.show" @close="closeDialog">
    <form @submit.prevent="update">
      <div class="flex flex-col space-y-5">
        <div>
          <div class="select-none text-sm text-gray-600 mb-2">Link</div>
          <PureInput type="url" id="input-link-url" v-model="inputLinkRef" />
        </div>
        <div>
          <div class="select-none text-sm text-gray-600 mb-2">Target</div>
          <SelectButton v-model="target" :options="options" optionLabel="label" />
        </div>
        

        <div class="flex flex-row justify-end space-x-3">
          <button type="button" class="rounded-md px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100"
            @click="closeDialog">
            Cancel
          </button>
          <button type="submit"
            class="rounded-md bg-blue-700 px-4 py-3 text-sm font-medium text-white hover:bg-opacity-80">
            Save
          </button>
        </div>
      </div>
    </form>
  </Dialog>
</template>
