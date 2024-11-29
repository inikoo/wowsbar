/**
 * author Vika Aqordi
 * created on 08-11-2024-14h-02m
 * github: https://github.com/aqordeno
 * copyright 2024
*/

import type { Component } from 'vue'
import ButtonVisibleLoggedIn from '@/Components/CMS/Fields/ButtonVisibleLoggedIn.vue'
import PanelProperties from '@/Components/CMS/Fields/PanelProperties.vue'
import SideEditorInputHTML from '@/Components/CMS/Fields/SideEditorInputHTML.vue'
import Border from '@/Components/CMS/Fields/Border.vue'
import Padding from '@/Components/CMS/Fields/Padding.vue'
import Margin from '@/Components/CMS/Fields/Margin.vue'
import Dimension from '@/Components/CMS/Fields/Dimension.vue'
import Link from '@/Components/CMS/Fields/Link.vue'
import Background from '@/Components/CMS/Fields/Background.vue'
import ButtonProperties from '@/Components/CMS/Fields/ButtonProperties.vue'
// import UploadImage from '@/Components/Pure/UploadImage.vue'
import Payments from '@/Components/CMS/Fields/Payment.vue'
// import Editor from "@/Components/Forms/Fields/BubleTextEditor/EditorForm.vue"
import socialMedia from '@/Components/CMS/Fields/SocialMedia.vue'
import Script from '@/Components/CMS/Fields/Script.vue'
import SelectLayout from '@/Components/CMS/Fields/SelectLayout.vue'
import InputText from 'primevue/inputtext'
import OverviewForm from '@/Components/CMS/Fields/OverviewForm.vue'
import ArrayPhone from '@/Components/CMS/Fields/ArrayPhone.vue'
// import NotFoundComponents from '@/Components/CMS/Webpage/NotFoundComponent.vue'

import { set } from 'lodash'


// Method: sent data from inside iframe, to parent (the browser)
export const iframeToParent = (data: any) => {
    if (window) {
        window.parent.postMessage(data, '*')
    }
}



export const getComponentProperties = (componentName: string) => {
    const components: Component = {
        'text': InputText,
        'editorhtml': SideEditorInputHTML,
        // 'upload_image': UploadImage,
        'payment_templates': Payments,
        // 'editor': Editor,
        'socialMedia': socialMedia,
        "VisibleLoggedIn": ButtonVisibleLoggedIn,
        "properties": PanelProperties,
        "background": Background,
        "border": Border,
        "padding": Padding,
        "margin": Margin,
        "dimension": Dimension,
        "button": ButtonProperties,
        "link": Link,
        "overview_form": OverviewForm,
        "layout_type": SelectLayout,
        "script": Script,
        "arrayPhone":ArrayPhone
    }
    
    return components[componentName] ?? 'NotFoundComponents'
}

export const getFormValue = (data: any, fieldKeys: string | string[]) => {
    const keys = Array.isArray(fieldKeys) ? fieldKeys : [fieldKeys];
    return keys.reduce((acc, key) => acc && acc[key], data) ?? null;
};

export const setFormValue = (mValue: any, fieldKeys: string | string[], newVal: any) => {
    const keys = Array.isArray(fieldKeys) ? fieldKeys : [fieldKeys];
    return set(mValue, keys, newVal);
};