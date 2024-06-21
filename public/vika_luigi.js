import 'https://cdn.luigisbox.com/autocomplete.js'; // For autocomplete

console.log("Hello world 123!!")

const luigiTrackerId = '483878-588294';

const LBInitAutocomplete = () => {
    AutoComplete({
        Layout: 'heromobile',
        TrackerId: luigiTrackerId,
        Locale: 'en',
        Types: [
            {
                name: 'Product',
                type: 'product',
                size: 7
            },
            {
                name: 'Query',
                type: 'query'
            },
            {
                name: 'Category',
                type: 'category'
            }
        ]
    }, '#inputSearchLuigi');
};

const loadCSS = (href) => {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
};

const loadInputLuigi = () => {
    const input = document.createElement('input');
    input.id = 'inputSearchLuigi';

    const divInputLuigi = document.getElementById('inputLuigi');
    divInputLuigi.appendChild(input);
    
};

document.addEventListener('DOMContentLoaded', async () => {
    loadCSS('https://cdn.luigisbox.com/autocomplete.css');
    loadInputLuigi();
    LBInitAutocomplete();

});