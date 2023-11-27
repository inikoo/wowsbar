import { trans } from "laravel-vue-i18n";
export default {
    queryLists: [
        { label:  trans("Email") , value: "email" },
        { label: trans("Phone") , value: "phone" },
        { label: trans("Address") , value: "address" },
    ],
    logic: [
        { label:  trans("All") , value: "all" },
        { label:  trans("Any"), value: "any" },
    ],
    contact: [
        { label: trans("Last Contact"), value: true },
        { label: trans("Never"), value: false },
    ],


    schemaForm: [
        {
<<<<<<< HEAD
            label:  trans("Prospect Can Contact By"),
            name: "prospect_can_contact_by",
=======
            label:  trans("Prospect with"),
            name: "prospect_with",
>>>>>>> 0db5b54788658860ebbb8f6e29fd949bb8a767e7
            information : trans("filter deliveries based on the prospect"),
            value : {
                fields : [],
                logic : 'all'
            },
        },
        {
            label: trans("Tags"),
            name: "tags",
            information :  trans("Filter by SEO tags"),
            value : {
                tag_ids : [],
                logic : 'all'
            },
        },
        {
<<<<<<< HEAD
            label:  trans("Prospect Last Contacted"),
=======
            label:  trans("Last contacted"),
>>>>>>> 0db5b54788658860ebbb8f6e29fd949bb8a767e7
            name: "prospect_last_contacted",
            information : trans("filter recipients based on the last mailshot sent to them"),
            value : {
                state : false,
                data: {
                    unit: "week",
                    quantity: 1,
                },
            }
        },
    ],

};
