export default {
    QueryLists: [
        { label: "Email", value: "email" },
        { label: "Phone", value: "phone" },
        { label: "Address", value: "address" },
    ],
    FilterTags: [
        { label: "All", value: "all" },
        { label: "Any", value: "any" },
    ],
    contact: [
        { label: "Last Contact", value: true },
        { label: "Never", value: false },
    ],

    defaultValue: {
        query: [],
        tag: {
            state: "all",
            tags: [],
        },
        last_contact: {
            state: false,
            data: {
                unit: "week",
                quantity: 1,
            },
        },
    },

    schemaForm: [
        {
            label: "Propspect by",
            name: "propspect_by",
            value : {
                by : []
            },
        },
        {
            label: "Tags",
            name: "tag",
            value : {
                tags : [],
                state : 'all'
            },
        },
        {
            label: "Last contacted",
            name: "last_contact",
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
