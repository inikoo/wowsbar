
// const abab = "<div>Hello from luigi.js</div>"
// document.body.innerHTML = abab

const scriptUrl = new URL(document.currentScript.src);

// Extract the `ulid` from the query parameters
const ulid = scriptUrl.searchParams.get('ulid');

// Use the ulid as needed
console.log('ULID:', ulid);


async function fetchAnnouncementData() {
    if (ulid) {
        try {
            // const announcementData = await fetch(`http://delivery.wowsbar.test/announcement/${ulid}`)
            //     .then(response => {
            //         if (!response.ok) {
            //             throw new Error('Network response was not ok ' + response.statusText);
            //         }
            //         return response.json();
            //     });

            // console.log('announcementData', announcementData);

            // createElementAfterBody();

            const iframe = document.createElement('iframe');
            iframe.src = `http://delivery.wowsbar.test/announcement/${ulid}`;
            iframe.id = `iframe-wowsbar-${ulid}`;
            iframe.setAttribute('allowTransparency', 'true');
            iframe.style.height = '0px'
            iframe.style.width = '100%';
            iframe.style.display = 'block';
            iframe.style.backgroundColor = 'transparent';
            iframe.style.border = 'none';
            iframe.style.zIndex = 9999;

            function adjustIframeHeight() {
                iframe.style.height = iframe.contentWindow.document.body.clientHeight + 'px';
                console.log('ffff', iframe.style.height)
            }
            
            iframe.onload = adjustIframeHeight;

            const body = document.body;

            body.insertBefore(iframe, body.firstChild);
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    } else {
        console.log("ulid is not exist");
    }
}

// Call the async function
fetchAnnouncementData();



// How to run
// const script = document.createElement('script')
//     script.src = 'http://delivery.wowsbar.test/announcement.js?v=1&ulid=01J9T4KWNQJM9BMMPHKGKY0AVK'
//     script.async = true
//     document.body.appendChild(script)