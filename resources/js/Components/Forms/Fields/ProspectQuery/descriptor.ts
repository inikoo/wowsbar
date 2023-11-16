export default {
    QueryLists: [ 'Email','Phone','Address'],
    FilterTags: ["All","Any","Not Set"],
    contact: [ {label : "Last Contact" , value : true}, {label : "Never" , value : false} ],
    defaultValue: {
        query: ['Email'],
        tag: {
            filter: "All",
            tags: [],
        },
        last_contact: {
            filter: false,
            data: {
                range: 'day',
                count: 1,
            },
        },
    },
};
