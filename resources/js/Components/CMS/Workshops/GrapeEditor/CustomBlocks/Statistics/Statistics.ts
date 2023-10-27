export const StatisticsBlock1 = () => {
    return {
        id: "statistic-block-1",
        class: "",
        category: "Statistics",
        label: "Blog One",
        content: `
        <style>
        .h2{
          font-family:inherit;
          font-weight:600;
          line-height:1.5;
          margin-bottom:0.5rem;
          color:rgb(50, 50, 93);
        }
        .h5, h2, h5{
          font-family:inherit;
          font-weight:600;
          line-height:1.5;
          margin-bottom:0.5rem;
          color:rgb(50, 50, 93);
        }
        .h5, h5{
          font-size:0.8125rem;
        }
        .bg-danger{
          background-color:rgb(245, 54, 92) !important;
        }
        .pt-5{
          padding-top:3rem !important;
        }
        .pb-8{
          padding-bottom:8rem !important;
        }
        .font-weight-bold{
          font-weight:600 !important;
        }
        a.text-success:hover, a.text-success:focus{
          color:rgb(36, 164, 109) !important;
        }
        .text-warning{
          color:rgb(251, 99, 64) !important;
        }
        a.text-warning:hover, a.text-warning:focus{
          color:rgb(250, 58, 14) !important;
        }
        .text-danger{
          color:rgb(245, 54, 92) !important;
        }
        a.text-danger:hover, a.text-danger:focus{
          color:rgb(236, 12, 56) !important;
        }
        .text-white{
          color:rgb(255, 255, 255) !important;
        }
        a.text-white:hover, a.text-white:focus{
          color:rgb(230, 230, 230) !important;
        }
        .text-muted{
          color:rgb(136, 152, 170) !important;
        }
        figcaption, main{
          display:block;
        }
        main{
          overflow-x:hidden;
          overflow-y:hidden;
        }
        .bg-yellow{
          background-color:rgb(255, 214, 0) !important;
        }
        .icon{
          width:3rem;
          height:3rem;
        }
        .icon i{
          font-size:2.25rem;
        }
        .icon-shape{
          display:inline-flex;
          padding-top:12px;
          padding-right:12px;
          padding-bottom:12px;
          padding-left:12px;
          text-align:center;
          border-top-left-radius:50%;
          border-top-right-radius:50%;
          border-bottom-right-radius:50%;
          border-bottom-left-radius:50%;
          align-items:center;
          justify-content:center;
        }
        @media print{
          *, ::before, ::after{
            box-shadow:none !important;
            text-shadow:none !important;
          }
          a:not(.btn){
            text-decoration-line:underline;
            text-decoration-thickness:initial;
            text-decoration-style:initial;
            text-decoration-color:initial;
          }
          p, h2{
            orphans:3;
            widows:3;
          }
          h2{
            break-after:avoid;
          }
          body{
            min-width:992px !important;
          }
        }
        @media (min-width: 992px){
          .col-lg-6{
            max-width:50%;
            flex-grow:0;
            flex-shrink:0;
            flex-basis:50%;
          }
        }
        @media (min-width: 1200px){
          .col-xl-3{
            max-width:25%;
            flex-grow:0;
            flex-shrink:0;
            flex-basis:25%;
          }
          .mb-xl-0{
            margin-bottom:0px !important;
          }
        }
        
        </style>
        <section data-id="statistic-block-1" data-type="html" data-group="statistic" class="wowsbar-block">
        <div class="main-content">
          <div class="header bg-gradient-primary pb-8 pt-5">
            <div class="container-fluid">
              <div class="header-body">
                <div class="row">
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                      <div class="card-body">
                        <div class="row">
                          <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Traffic
                            </h5>
                            <span class="h2 font-weight-bold mb-0">350,897</span>
                          </div>
                          <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                              <i class="fas fa-chart-bar">
                              </i>
                            </div>
                          </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                          <span class="text-success mr-2"><i class="fa fa-arrow-up">
                            </i> 3.48%</span>
                          <span class="text-nowrap">Since last month</span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                      <div class="card-body">
                        <div class="row">
                          <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">New users
                            </h5>
                            <span class="h2 font-weight-bold mb-0">2,356</span>
                          </div>
                          <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                              <i class="fas fa-chart-pie">
                              </i>
                            </div>
                          </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                          <span class="text-danger mr-2"><i class="fas fa-arrow-down">
                            </i> 3.48%</span>
                          <span class="text-nowrap">Since last week</span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                      <div class="card-body">
                        <div class="row">
                          <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Sales
                            </h5>
                            <span class="h2 font-weight-bold mb-0">924</span>
                          </div>
                          <div class="col-auto">
                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                              <i class="fas fa-users">
                              </i>
                            </div>
                          </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                          <span class="text-warning mr-2"><i class="fas fa-arrow-down">
                            </i> 1.10%</span>
                          <span class="text-nowrap">Since yesterday</span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                      <div class="card-body">
                        <div class="row">
                          <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Performance
                            </h5>
                            <span class="h2 font-weight-bold mb-0">49,65%</span>
                          </div>
                          <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                              <i class="fas fa-percent">
                              </i>
                            </div>
                          </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                          <span class="text-success mr-2"><i class="fas fa-arrow-up">
                            </i> 12%</span>
                          <span class="text-nowrap">Since last month</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Page content -->
        </div>
      </section>
   `,
    };
};

export const StatisticsBlock2 = () => {
    return {
        id: "Statistic-block-2",
        class: "",
        category: "Statistics",
        label: "Blog Two",
        content: `
        <section data-id="statistic-block-1" data-type="html" data-group="statistic" class="wowsbar-block" data-gjs-editable="false" data-gjs-droppable="false">
        <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Sales</h4>
              <div class="stats-figure">$12,628</div>
              <div class="stats-meta text-success">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg> 20%</div>
            </div><!--//app-card-body-->
            <a class="app-card-link-mask" href="#"></a>
          </div><!--//app-card-->
        </div><!--//col-->
        
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Expenses</h4>
              <div class="stats-figure">$2,250</div>
              <div class="stats-meta text-success">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
</svg> 5% </div>
            </div><!--//app-card-body-->
            <a class="app-card-link-mask" href="#"></a>
          </div><!--//app-card-->
        </div><!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Projects</h4>
              <div class="stats-figure">23</div>
              <div class="stats-meta">
                Open</div>
            </div><!--//app-card-body-->
            <a class="app-card-link-mask" href="#"></a>
          </div><!--//app-card-->
        </div><!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Invoices</h4>
              <div class="stats-figure">6</div>
              <div class="stats-meta">New</div>
            </div><!--//app-card-body-->
            <a class="app-card-link-mask" href="#"></a>
          </div><!--//app-card-->
        </div><!--//col-->
    </section>
   `,
    };
};

export const StatisticsBlock3 = () => {
    return {
        id: "Statistic-block-3",
        class: "",
        category: "Statistics",
        label: "Blog Three",
        content: `
        <section data-id="statistic-block-1" data-type="html" data-group="statistic" class="wowsbar-block" data-gjs-editable="false" data-gjs-droppable="false">
<div class="container">
  <div class="row gy-4 gy-lg-0 align-items-lg-center">
    <div class="col-12 col-lg-6">
      <img class="img-fluid rounded" loading="lazy" src="https://dummyimage.com/358x295" alt="">
    </div>
    <div class="col-12 col-lg-6">
      <div class="row justify-content-xl-end">
        <div class="col-12 col-xl-11">
          <div class="row gy-4 gy-sm-0 overflow-hidden">
            <div class="col-12 col-sm-6">
              <div class="card border-0 border-bottom border-primary shadow-sm mb-4">
                <div class="card-body text-center p-4 p-xxl-5">
                  <h3 class="display-2 fw-bold mb-2">60</h3>
                  <p class="fs-5 mb-0 text-secondary">Finished Projects</p>
                </div>
              </div>
              <div class="card border-0 border-bottom border-primary shadow-sm">
                <div class="card-body text-center p-4 p-xxl-5">
                  <h3 class="display-2 fw-bold mb-2">18k+</h3>
                  <p class="fs-5 mb-0 text-secondary">Issues Solved</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="card border-0 border-bottom border-primary shadow-sm mt-lg-6 mt-xxl-8 mb-4">
                <div class="card-body text-center p-4 p-xxl-5">
                  <h3 class="display-2 fw-bold mb-2">10k+</h3>
                  <p class="fs-5 mb-0 text-secondary">Happy Customers</p>
                </div>
              </div>
              <div class="card border-0 border-bottom border-primary shadow-sm">
                <div class="card-body text-center p-4 p-xxl-5">
                  <h3 class="display-2 fw-bold mb-2">78</h3>
                  <p class="fs-5 mb-0 text-secondary">Awwwards</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    </section>
   `,
    };
};

// export const StatisticsBlock4 = () => {
//     return {
//         id: "Statistic-block-4",
//         class: "",
//         category: "Statistics",
//         label: "Blog Four",
//         content: `
//         <section data-id="statistic-block-1" data-type="html" data-group="statistic" class="wowsbar-block relative bg-white" data-gjs-editable="false" data-gjs-droppable="false">
//             <div class="mx-auto grid max-w-7xl lg:grid-cols-2" data-gjs-editable="false" data-gjs-droppable="false">
//               <div class="gjs-row" id="ijb72">
//                 <div class="gjs-cell" id="i5qp8">
//                 <img src="https://dummyimage.com/753x739" data-gjs-removable="false" class="h-full aspect-square" alt="Description of the image">
//                 </div>
//               </div>
//               <div class="px-6 pb-24 pt-16 sm:pb-32 sm:pt-20 lg:col-start-2 lg:px-8 lg:pt-32">
//                 <div class="mx-auto max-w-2xl lg:mr-0 lg:max-w-lg">
//                   <h2 class="text-base font-semibold leading-8 text-indigo-600">Our track record
//                   </h2>
//                   <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Trusted by thousands of creators worldwide
//                   </p>
//                   <p class="mt-6 text-lg leading-8 text-gray-600">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.
//                   </p>
//                   <dl class="mt-16 grid max-w-xl grid-cols-1 gap-8 sm:mt-20 sm:grid-cols-2 xl:mt-16">
//                     <div class="flex flex-col gap-y-3 border-l border-gray-900-10 pl-6">
//                       <dt class="text-sm leading-6 text-gray-600">Creators on the platform
//                       </dt>
//                       <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">8,000+</dd>
//                     </div>
//                     <div class="flex flex-col gap-y-3 border-l border-gray-900-10 pl-6">
//                       <dt class="text-sm leading-6 text-gray-600">Flat platform fee
//                       </dt>
//                       <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">3%</dd>
//                     </div>
//                     <div class="flex flex-col gap-y-3 border-l border-gray-900-10 pl-6">
//                       <dt class="text-sm leading-6 text-gray-600">Uptime guarantee
//                       </dt>
//                       <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">99.9%</dd>
//                     </div>
//                     <div class="flex flex-col gap-y-3 border-l border-gray-900-10 pl-6">
//                       <dt class="text-sm leading-6 text-gray-600">Paid out to creators
//                       </dt>
//                       <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">$70M</dd>
//                     </div>
//                   </dl>
//                 </div>
//               </div>
//             </div>
//     </section>
//    `,
//     };
// };
