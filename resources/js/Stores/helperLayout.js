import { defineStore } from "pinia";

export const useHelperLayout = defineStore("helperLayout", {
    state: () => (
        {
            popoverToolsEditor: false
        }
    )

});

