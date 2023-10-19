export const headerBlock1 = () => {
    return {
        disable: false,
        activate: false,
        category: "Header",
        label: `<svg fill="none" viewBox="0 0 266 150" width="100%" height="100%"><path fill="#FFFFFF" d="M0 0h266v150H0z"></path><path stroke="#E2E8F0" d="M266 38.5H0" fill="none"></path><rect x="217" y="14" width="29" height="10" rx="2" fill="#CBD5E0"></rect><circle cx="29" cy="19" r="9" fill="#6366F1"></circle><rect x="150.132" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="171.264" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="192.396" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="129" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect></svg>`,
        id: "header-block-1",
        content: `
        <style>
            @media (max-width: 767px) {
                .navbar-brand img {
                    display: none;
                }
            }
        </style>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="/">
                    <img src="https://dummyimage.com/45x30" height="15" alt="Logo">
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Projects</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="/login" data-wowsbar-element="login" class="btn btn-primary" style="margin: 0px 5px">Log
                        in</a>
                </div>
            </div>
        </div>
    </nav> 
`,
    };
};

export const headerBlock3 = () => {
    return {
        disable: false,
        activate: false,
        category: "Header",
        label: `<svg fill="none" viewBox="0 0 266 150" width="100%" height="100%"><path fill="#FFFFFF" d="M0 0h266v150H0z"></path><path stroke="#E2E8F0" d="M266 38.5H0" fill="none"></path><rect x="217" y="14" width="29" height="10" rx="2" fill="#CBD5E0"></rect><circle cx="133" cy="19" r="9" fill="#6366F1"></circle><rect x="62.264" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="41.132" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="83.396" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="20" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect></svg>`,
        id: "header-block-3",
        content: `
        <nav class="navbar navbar-expand-lg fixed-top bg-light navbar-light">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
        <div class="container d-flex justify-content-center collapse navbar-collapse">
            <div class="row">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <a class="navbar-brand" href="#">
                        <img id="MDB-logo" src="https://dummyimage.com/45x30" draggable="false" height="30" alt="Logo">
                    </a>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav align-items-center mx-auto">
                            <li class="nav-item">
                                <a class="nav-link mx-2" href="#!"><i class="fas fa-plus-circle pe-2"></i>Post</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2" href="#!"><i class="fas fa-bell pe-2"></i>Alerts</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link mx-2" href="/login" data-wowsbar-element="login"><i class="fas fa-bell pe-2"></i>Log In</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
`,
    };
};

export const headerBlock4 = () => {
    return {
        disable: false,
        activate: false,
        category: "Header",
        label: `<svg fill="none" viewBox="0 0 266 150" width="100%" height="100%"><path fill="#FFFFFF" d="M0 0h266v150H0z"></path><path stroke="#E2E8F0" d="M266 38.5H0" fill="none"></path><rect x="217" y="14" width="29" height="10" rx="2" fill="#CBD5E0"></rect><circle cx="29" cy="19" r="9" fill="#6366F1"></circle><rect x="129.264" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="108.132" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="150.396" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect><rect x="87" y="17" width="16.604" height="4" rx="2" fill="#4A5568"></rect></svg>`,
        id: "header-block-4",
        content: `
        <Header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 border-bottom" style='padding:0px 10px'>
          <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
          <img src="https://dummyimage.com/45x30" alt="Description of the image">
          </a>
    
          <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 link-secondary">Home</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Features</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 link-dark">About</a></li>
          </ul>
    
          <div class="col-md-3 text-end">
          <a href="/login" data-wowsbar-element="login" class="btn btn-primary" style='margin:0px 5px'>Log in</a>
          </div>
        </Header>
`,
    };
};
