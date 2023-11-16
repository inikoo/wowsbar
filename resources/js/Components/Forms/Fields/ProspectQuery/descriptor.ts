export default {
    QueryLists: [ 'Email','Phone','Address'],
    FilterTags: ["All","Any","Not Set"
    ],
    contact: ["Never", "Last Contact"],

    defaultValue: {
        query: ['Email'],
        tag: {
            filter: "All",
            tags: [],
        },
        last_contact: {
            filter: "Never",
            data: {
                range: null,
                count: null,
            },
        },
    },
};
