<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 21 Jul 2023 22:21:38 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue";
import axios from "axios";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faTrashAlt,
  faAlignJustify,
  faCog,
  faImage,
  faLock,
} from "@/../private/pro-light-svg-icons";
// import {  } from "@/../private/pro-regular-svg-icons"
import { faEye, faEyeSlash } from "@/../private/pro-solid-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import draggable from "vuedraggable";
import { ulid } from "ulid";
import Input from "@/Components/Forms/Fields/Input.vue";
import { trans } from "laravel-vue-i18n";
import SlideWorkshop from "@/Components/Workshop/SlideWorkshop.vue";
import Button from "../Elements/Buttons/Button.vue";
import { get, isNull } from "lodash";
import { router } from "@inertiajs/vue3";
import SliderCommonWorkshop from "./SliderCommonWorkshop.vue";
import Modal from "./Modal/Modal.vue";
import LibrariesImage from "./LibrariesImage.vue";
import CropImage from "./CropImage/CropImage.vue";
import Image from "@/Components/Image.vue";
library.add(faEye, faEyeSlash, faTrashAlt, faAlignJustify, faCog, faImage, faLock);
interface CornersPositionData {
  data: {
    text: string;
    target: string;
  };
  type: string;
}

interface Corners {
  topLeft?: CornersPositionData;
  topRight?: CornersPositionData;
  bottomLeft?: CornersPositionData;
  bottomRight?: CornersPositionData;
}

const props = defineProps<{
  data: {
    common: {
      centralStage: {
        subtitle?: string;
        text?: string;
        title?: string;
      };
      corners: Corners;
    };
    components: Array<{
      id: number;
      image_id: number;
      image_source: string;
      ulid?: number;
      layout: {
        link?: string;
        centralStage: {
          title?: string;
          subtitle?: string;
          // text?: string,
          // footer?: string
        };
      };
      visibility: boolean;
      corners: Corners;
      imageAlt: string;
      link: string;
    }>;
    delay: number;
  };
  imagesUploadRoute: {
    name: string;
    arguments: string;
  };
  user: Object;
}>();

const emits = defineEmits<{
  (e: "jumpToIndex", id: number): void;
}>();

const isDragging = ref(false);
const fileInput = ref(null);
const currentComponentBeenEdited = ref(null);
const commonEditActive = ref(false);
const isOpenLibrariesImage = ref(false);
const addFiles = ref([]);
const closeModalisOpenLibrariesImage = () => {
  isOpenLibrariesImage.value = false;
};

const isOpenCropModal = ref(false);

const closeModalisOpenCropModal = () => {
  addFiles.value = [];
  isOpenCropModal.value = false;
  fileInput.value.value = "";
};

const addComponent = async (element) => {
  addFiles.value = element.target.files;
  isOpenCropModal.value = true;
};

const removeComponent = (file) => {
  const index = props.data.components.findIndex((item) => item.ulid === file.ulid);
  if (index !== -1) {
    console.log(currentComponentBeenEdited.value);
    if (
      currentComponentBeenEdited.value &&
      currentComponentBeenEdited.value.ulid === props.data.components[index].ulid
    ) {
      const nextIndex = index + 1;
      selectComponentForEdition(
        nextIndex < props.data.components.length
          ? props.data.components[nextIndex]
          : props.data.components.filter((item) => item.ulid !== null)[0]
      );
    }
    props.data.components[index].ulid = null;
  } else {
    console.log("Index not found");
  }
};

const dragover = (e) => {
  e.preventDefault();
  isDragging.value = true;
};

const dragleave = () => {
  isDragging.value = false;
};

const drop = (e) => {
  e.preventDefault();
  addFiles.value = e.dataTransfer.files;
  isOpenCropModal.value = true;
  isDragging.value = false;
};

const selectComponentForEdition = (slide) => {
  const componentToEdit = props.data.components.find((item) => item.ulid === slide.ulid);

  if (!componentToEdit) {
    console.log("Component not found");
    return;
  }

  if (slide.user && slide.user !== props.user.username) {
    console.log("Cannot edit: Component already in use");
  } else {
    const oldComponentToEdit = props.data.components.find(
      (item) => item.ulid === get(currentComponentBeenEdited.value, "ulid")
    );

    if (oldComponentToEdit) {
      oldComponentToEdit.user = null;
    }

    componentToEdit.user = props.user.username;
    if (commonEditActive) {
      props.data.common.user = null;
    }
    commonEditActive.value = false;
    currentComponentBeenEdited.value = slide;
    if (!isNull(_SlideWorkshop.value)) _SlideWorkshop.value.current = 0;
  }
};

watch(
  currentComponentBeenEdited,
  (value, oldValue) => {
    if (value !== null) {
      const component = [...props.data.components]; // Create a shallow copy of the components array
      const index = component.findIndex((item) => item.ulid === value.ulid);
      if (index !== -1) {
        component[index] = { ...value };
        props.data.components = component;
      }
    }
  },
  { deep: true }
);

// To change visibility of the each slide
const changeVisibility = (slide: any) => {
  const index = props.data.components.findIndex((item) => item.ulid === slide.ulid);
  if (index !== -1) {
    props.data.components[index].hasOwnProperty("visibility")
      ? (props.data.components[index].visibility = !props.data.components[index]
          .visibility)
      : (props.data.components[index].visibility = false);
  }
};

const ComponentsBlueprint = ref([
  {
    title: "Background & Link",
    icon: ["fal", "fa-image"],
    fields: [
      {
        name: "image",
        type: "slideBackground",
        label: trans("Image"),
        value: ["image"],
        uploadRoute: props.imagesUploadRoute,
      },
      {
        name: ["layout", "link"],
        type: "text",
        label: trans("Link"),
        value: ["layout", "link"],
        prefix: "https://",
      },
    ],
  },
  {
    title: "corners",
    icon: ["fal", "fa-expand-arrows"],
    fields: [
      {
        name: ["layout", "corners"],
        type: "corners",
        label: null,
        value: null,
        optionType: ["cornerFooter", "cornerText", "linkButton"],
      },
    ],
  },
  {
    title: "central stage",
    icon: ["fal", "fa-align-center"],
    fields: [
      {
        name: ["layout", "centralStage", "title"],
        type: "text",
        label: trans("Title"),
        value: ["layout", "centralStage", "title"],
      },
      {
        name: ["layout", "centralStage", "subtitle"],
        type: "text",
        label: trans("subtitle"),
        value: ["layout", "centralStage", "subtitle"],
      },
      {
        name: ["layout", "centralStage", "style", "fontFamily"],
        type: "select",
        label: trans("Font Family"),
        value: ["layout", "centralStage", "style", "fontFamily"],
        options: [
          "Arial",
          "Comfortaa",
          "Lobster",
          "Laila",
          "Port Lligat Slab",
          "Playfair",
          "Quicksand",
          "Times New Roman",
          "Yatra One",
        ],
      },
      {
        name: ["layout", "centralStage", "style", "fontSize"],
        type: "radio",
        label: trans("Font Family"),
        value: ["layout", "centralStage", "style", "fontSize"],
        defaultValue: { fontTitle: "27px", fontSubtitle: "14px" },
        options: [
          { label: "Small", value: { fontTitle: "21px", fontSubtitle: "12px" } },
          { label: "Medium", value: { fontTitle: "27px", fontSubtitle: "14px" } },
          { label: "large", value: { fontTitle: "34px", fontSubtitle: "16px" } },
          { label: "Extra large", value: { fontTitle: "42px", fontSubtitle: "19px" } },
          {
            label: "Double extra large",
            value: { fontTitle: "70px", fontSubtitle: "25px" },
          },
        ],
      },
      {
        name: ["layout", "centralStage", "style", "color"],
        type: "colorpicker",
        label: trans("color"),
        value: ["layout", "centralStage", "style", "color"],
      },
    ],
  },
  {
    title: "delete",
    icon: ["fal", "fa-trash-alt"],
    fields: [
      {
        name: ["layout", "centralStage", "title"],
        type: "delete",
        label: trans("Title"),
        value: ["layout", "centralStage", "title"],
      },
    ],
  },
]);

const CommonBlueprint = ref([
  {
    title: "Duration",
    icon: ["fal", "stopwatch"],
    fields: [
      {
        name: "delay",
        type: "range",
        label: trans("Duration"),
        value: null,
        timeRange: {
          min: "2.5",
          max: "15",
          step: "0.5",
          range: ["2.5", "5", "7.5", "10", "12.5", "15"],
        },
      },
    ],
  },
  {
    title: "corners",
    icon: ["fal", "fa-expand-arrows"],
    fields: [
      {
        name: ["common", "corners"],
        type: "corners",
        label: null,
        value: null,
        optionType: ["cornerFooter", "cornerText", "linkButton", "slideControls"],
      },
    ],
  },
  {
    title: "central stage",
    icon: ["fal", "fa-align-center"],
    fields: [
      {
        name: ["common", "centralStage", "title"],
        type: "text",
        label: trans("Title"),
        value: ["common", "centralStage", "title"],
      },
      {
        name: ["common", "centralStage", "subtitle"],
        type: "text",
        label: trans("subtitle"),
        value: ["common", "centralStage", "subtitle"],
      },
      {
        name: ["common", "centralStage", "style", "fontFamily"],
        type: "select",
        label: trans("Font Family"),
        value: ["common", "centralStage", "style", "fontFamily"],
        options: [
          "Arial",
          "Comfortaa",
          "Lobster",
          "Laila",
          "Port Lligat Slab",
          "Playfair",
          "Quicksand",
          "Times New Roman",
          "Yatra One",
        ],
      },
      {
        name: ["common", "centralStage", "style", "fontSize"],
        type: "radio",
        label: trans("Font Family"),
        value: ["common", "centralStage", "style", "fontSize"],
        defaultValue: { fontTitle: "27px", fontSubtitle: "14px" },
        options: [
          { label: "Small", value: { fontTitle: "21px", fontSubtitle: "12px" } },
          { label: "Medium", value: { fontTitle: "27px", fontSubtitle: "14px" } },
          { label: "large", value: { fontTitle: "34px", fontSubtitle: "16px" } },
          { label: "Extra large", value: { fontTitle: "42px", fontSubtitle: "19px" } },
          {
            label: "Double extra large",
            value: { fontTitle: "70px", fontSubtitle: "25px" },
          },
        ],
      },
      {
        name: ["common", "centralStage", "style", "color"],
        type: "colorpicker",
        label: trans("color"),
        value: ["common", "centralStage", "style", "color"],
      },
    ],
  },
]);

const _SlideWorkshop = ref(null);

const setCommonEdit = () => {
  if (props.data.common.user && props.data.common.user !== props.user.username) {
    console.log("masih dipakek");
  } else {
    const oldComponentToEdit = props.data.components.find(
      (item) => item.ulid === get(currentComponentBeenEdited.value, "ulid")
    );
    props.data.common.user = props.user.username;
    if (oldComponentToEdit) {
      oldComponentToEdit.user = null;
    }

    commonEditActive.value = !commonEditActive.value;

    if (commonEditActive.value) {
      currentComponentBeenEdited.value = null;
    } else {
      currentComponentBeenEdited.value = props.data.components[0];
    }
  }
};

const uploadImageRespone = (res) => {
  let setData = [];
  for (const set of res.data) {
    setData.push({
      id: null,
      ulid: ulid(),
      layout: {
        imageAlt: set.name,
      },
      image: set,
      visibility: true,
    });
  }
  const newFiles = [...setData];
  props.data.components = [...props.data.components, ...newFiles];
  isOpenCropModal.value = false;
};
</script>

<template>
  <div class="flex flex-grow gap-2.5">
    <Modal :isOpen="isOpenLibrariesImage" @onClose="closeModalisOpenLibrariesImage">
      <div>
        <LibrariesImage />
      </div>
    </Modal>

    <Modal :isOpen="isOpenCropModal" @onClose="closeModalisOpenCropModal">
      <div>
        <CropImage
          :data="addFiles"
          :imagesUploadRoute="props.imagesUploadRoute"
          :respone="uploadImageRespone"
        />
      </div>
    </Modal>

    <div
      class="p-2.5 border rounded h-fit shadow"
      :class="[commonEditActive ? 'w-[30%] lg:w-2/4' : 'w-[100%]']"
      v-if="data.components"
      @dragover="dragover"
      @dragleave="dragleave"
      @drop="drop"
    >
      <!-- Common Properties -->
      <div
        :class="[
          'p-2 mb-2 md:pl-3 cursor-pointer space-x-3 md:space-x-2 border flex flex-row items-center md:block',
          commonEditActive
            ? 'bg-gray-200/60 font-medium border-l-4 border-l-orange-500'
            : 'hover:bg-gray-100 border-gray-300',
        ]"
        @click="setCommonEdit"
      >
        <FontAwesomeIcon
          v-if="props.data.common.user == props.user.username || !props.data.common.user"
          icon="fal fa-cog"
          class="text-xl md:text-base"
          aria-hidden="true"
        />
        <FontAwesomeIcon
          v-else="
            props.data.common.user == props.user.username || !props.data.common.user
          "
          :icon="['fal', 'lock']"
          class=""
          aria-hidden="true"
        />
        <span class="text-gray-600 text-sm">{{ trans("Common properties") }}</span>
      </div>

      <!-- Slides/Drag area -->
      <div class="mb-2 text-lg font-medium">{{ trans("Slides") }}</div>
      <draggable
        :list="data.components"
        group="slide "
        item-key="ulid"
        handle=".handle"
        :onChange="(e: any) => emits('jumpToIndex', e.moved.newIndex)"
      >
        <template #item="{ element: slide }">
          <div
            @mousedown="
              selectComponentForEdition(slide),
                emits(
                  'jumpToIndex',
                  data.components.findIndex((component) => {
                    return component.id == slide.id;
                  })
                )
            "
            v-if="slide.ulid"
            :class="[
              'grid grid-flow-col relative sm:py-1 border mb-2 items-center justify-between hover:cursor-pointer',
              slide.ulid == get(currentComponentBeenEdited, 'ulid')
                ? 'border-l-orange-500 border-l-4 bg-gray-200/60 text-gray-600 font-medium'
                : 'border-gray-300',
              slide.user != props.user.username && slide.user
                ? 'border-l-red-500 border-l-4 bg-gray-200/60 text-gray-600 font-medium'
                : 'border-gray-300',
            ]"
          >
            <div class="grid grid-flow-col gap-x-1 lg:gap-x-0 ssm:py-1 lg:py-0">
              <!-- Icon: Bars, class 'handle' to grabable -->
              <FontAwesomeIcon
                icon="fal fa-bars"
                class="handle p-1 text-xs sm:text-base sm:p-2.5 text-gray-700 cursor-grab place-self-center"
              />

              <!-- Image slide -->
              <div class="h-10 w-10 sm:w-10 flex items-center justify-center py-1">
                <Image :src="slide.image.thumbnail"></Image>
              </div>

              <!-- Label slide -->
              <div
                class="hidden lg:inline-flex overflow-hidden whitespace-nowrap overflow-ellipsis pl-2 leading-tight flex-auto items-center"
              >
                <div
                  class="overflow-hidden whitespace-nowrap overflow-ellipsis lg:text-xs xl:text-sm"
                >
                  {{ slide?.layout?.imageAlt ?? "Image " + slide.id }}
                </div>
              </div>
            </div>

            <!-- Button: Show/hide, delete slide -->
            <div class="flex justify-center items-center pr-2 justify-self-end">
              <button
                class="px-2 py-1"
                type="button"
                v-if="slide.user == props.user.username || !slide.user"
                @click="changeVisibility(slide)"
                title="Show/hide the slide"
              >
                <FontAwesomeIcon
                  v-if="slide.hasOwnProperty('visibility') ? slide.visibility : true"
                  icon="fas fa-eye"
                  class="text-xs sm:text-sm text-gray-400 hover:text-gray-500"
                />
                <FontAwesomeIcon
                  v-else
                  icon="fas fa-eye-slash"
                  class="text-xs sm:text-sm text-gray-300 hover:text-gray-400/70"
                />
              </button>
              <button class="px-2 py-1" type="button" v-else>
                <font-awesome-icon
                  :icon="['fal', 'lock']"
                  class="text-xs sm:text-sm text-gray-300 hover:text-gray-400/70"
                />
              </button>
            </div>
          </div>
        </template>
      </draggable>

      <!-- Button: Add slide, Libraries -->
      <div class="flex flex-wrap md:flex-row gap-x-2 gap-y-1 md:gap-y-0 max-w-fit">
        <Button :style="`secondary`" icon="fas fa-plus" size="xs" class="relative">
          {{ trans("Add slide") }}
          <label
            class="bg-transparent inset-0 absolute inline-block cursor-pointer"
            id="input-slide-large-mask"
            for="fileInput"
          />
          <input
            ref="fileInput"
            type="file"
            multiple
            name="file"
            id="fileInput"
            @change="addComponent"
            accept="image/*"
            class="absolute cursor-pointer rounded-md border-gray-300 sr-only"
          />
        </Button>

        <Button
          :style="`tertiary`"
          icon="fal fa-image"
          size="xs"
          class="relative"
          @click="isOpenLibrariesImage = !isOpen"
        >
          {{ trans("Libraries") }}
        </Button>
      </div>
      <div class="text-xs text-gray-400 py-1">
        The recommended image size is 1800 x 450
      </div>
    </div>

    <!-- The Editor: Common -->
    <div class="w-full border border-gray-300" v-if="commonEditActive">
      <SliderCommonWorkshop
        :currentComponentBeenEdited="props.data"
        :blueprint="CommonBlueprint"
        ref="_SlideWorkshop"
      ></SliderCommonWorkshop>
    </div>

    <!-- The Editor: Slide -->
    <div class="w-full border border-gray-300" v-if="currentComponentBeenEdited != null">
      <SlideWorkshop
        :common="data.common"
        :currentComponentBeenEdited="currentComponentBeenEdited"
        :blueprint="ComponentsBlueprint"
        ref="_SlideWorkshop"
        :remove="removeComponent"
      ></SlideWorkshop>
    </div>
  </div>
</template>
