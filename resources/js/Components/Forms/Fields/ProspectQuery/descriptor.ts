export default {
    QueryLists: [ 'Email','Phone','Address'],
    FilterTags: ["All","Any","Not Set"
    ],
    contact: ["Never", "Last Contact"],

    defaultValue: {
        query: ['Email'],
        tag: {
            filter: "all",
            tag: ["seo", "ppc", "ssm", "developer"],
        },
        last_contact: {
            filter: "last contact",
            data: {
                range: "week",
                count: 1,
            },
        },
    },
};
