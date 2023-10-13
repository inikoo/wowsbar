export const PricingBlock1 = () => {
    return {
        id: "Pricing-block-1",
        class: "",
        category: "Pricing",
        label: "Pricing one",
        content: `
        <style>
        .card {
          border:none;
          padding: 10px 50px;
        }
    
        .card::after {
          position: absolute;
          z-index: -1;
          opacity: 0;
          -webkit-transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
          transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
    
        .card:hover {
    
    
          transform: scale(1.02, 1.02);
          -webkit-transform: scale(1.02, 1.02);
          backface-visibility: hidden; 
          will-change: transform;
          box-shadow: 0 1rem 3rem rgba(0,0,0,.75) !important;
        }
    
        .card:hover::after {
          opacity: 1;
        }
    
        .card:hover .btn-outline-primary{
          color:white;
          background:#007bff;
        }
    
      </style>
        <section data-id="pricing-block-1" data-type="html" data-group="statistic" class="wowsbar-block container-fluid" data-gjs-editable="false" data-gjs-droppable="false"  style="background: linear-gradient(90deg, #00C9FF 0%, #92FE9D 100%);">
          <div class="container p-5" data-gjs-editable="false" data-gjs-droppable="false" >
            <div class="row">
              <div class="col-lg-4 col-md-12 mb-4">
                <div class="card h-100 shadow-lg">
                  <div class="card-body">
                    <div class="text-center p-3">
                      <h5 class="card-title">Basic</h5>
                      <small>Individual</small>
                      <br><br>
                      <span class="h2">$8</span>/month
                      <br><br>
                    </div>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Cras justo odio</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Dapibus ac facilisis in</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Vestibulum at eros</li>
                  </ul>
                  <div class="card-body text-center">
                    <button class="btn btn-outline-primary btn-lg" style="border-radius:30px">Select</button>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-12 mb-4">
                <div class="card h-100 shadow-lg">
                  <div class="card-body">
                    <div class="text-center p-3">
                      <h5 class="card-title">Standard</h5>
                      <small>Small Business</small>
                      <br><br>
                      <span class="h2">$20</span>/month 
                      <br><br>
                    </div>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Cras justo odio</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Dapibus ac facilisis in</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Vestibulum at eros</li>
                  </ul>
                  <div class="card-body text-center">
                    <button class="btn btn-outline-primary btn-lg" style="border-radius:30px">Select</button>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-12 mb-4">
                <div class="card h-100 shadow-lg">
                  <div class="card-body">
                    <div class="text-center p-3">
                      <h5 class="card-title">Premium</h5>
                      <small>Large Companies</small>
                      <br><br>
                      <span class="h2">$40</span>/month
                      <br><br>
                    </div>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Cras justo odio</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Dapibus ac facilisis in</li>
                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                      <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                    </svg> Vestibulum at eros</li>
                  </ul>
                  <div class="card-body text-center">
                    <button class="btn btn-outline-primary btn-lg" style="border-radius:30px">Select</button>
                  </div>
                </div>
              </div>
            </div>    
    </section>
   `,
    };
};

export const PricingBlock2 = () => {
    return {
        id: "Pricing-block-2",
        class: "",
        category: "Pricing",
        label: "Pricing two",
        content: `
      <style>
      .pricingTable{
        margin-top:30px;
        text-align: center;
        position: relative;
    }
    .pricingTable .pricing_heading:after{
        content: "";
        width: 36px;
        height: 29.5%;
        background:#EF476F;
        position: absolute;
        top: -1px;
        right: 0;
        z-index: 2;
        transform: skewY(45deg) translateY(18px);
        transition: all 0.4s ease 0s;
    }
    .pricingTable .title{
        font-size: 20px;
        font-weight: 700;
        line-height: 30px;
        color: #000;
        text-transform: uppercase;
        background: #EF476F;
        padding: 15px 0 0;
        margin: 0 35px 0 0;
        transition: all 0.4s ease 0s;
    }
    .pricingTable .value{
        display: block;
        font-size: 35px;
        font-weight: 700;
        color: #000;
        background: #EF476F;
        padding: 5px 0 10px;
        margin: 0 35px 0 0;
        transition: all 0.4s ease 0s;
    }
    .pricingTable:hover .title,
    .pricingTable:hover .value{
        color: #fff;
    }
    .pricingTable .month{
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        text-transform: uppercase;
    }
    .pricingTable .content{
        border-left: 1px solid #f2f2f2;
        position: relative;
    }
    .pricingTable .content:after{
        content: "";
        width: 35px;
        height: 100%;
        background: #f8f8f8;
        box-shadow: 9px 9px 20px #ddd;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
        transform: skewY(-45deg) translateY(-18px);
        transition: all 0.4s ease 0s;
    }
    .pricingTable .content ul{
        padding: 0;
        margin: 0 35px 0 0;
        list-style: none;
        background: #fff;
    }
    .pricingTable .content ul li{
        display: block;
        font-size: 15px;
        color: #333;
        line-height: 23px;
        padding: 11px 0;
        border-bottom: 1px solid #f2f2f2;
    }
    .pricingTable .link{
        background: #fff;
        padding: 20px 0;
        margin-right: 35px;
        border-bottom: 1px solid #f2f2f2;
    }
    .pricingTable .link a{
        display: inline-block;
        padding: 9px 20px;
        font-weight: 700;
        color: #EF476F;
        text-transform: uppercase;
        border: 1px solid #EF476F;
        background: #fff;
        transition: all 0.4s ease 0s;
    }
    .pricingTable:hover .link a{
        color: #fff;
        background: #06D6A0;
        border: 1px solid #06D6A0;
    }
    .pricingTable:hover .pricing_heading:after,
    .pricingTable:hover .title,
    .pricingTable:hover .value{
        background:#06D6A0;
    }
    @media only screen and (max-width: 990px){
        .pricingTable{
            margin-bottom: 35px;
        }
    }
  </style>
      <section data-id="pricing-block-2" data-type="html" data-group="statistic" class="wowsbar-block container-fluid py-20" data-gjs-editable="false" data-gjs-droppable="false">
      <div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="pricingTable">
                <div class="pricing_heading">
                    <h3 class="title">Pricing Plan</h3>
                    <span class="value">
                        $12.99
                        <span class="month">per month</span>
                    </span>
                </div>
                <div class="content">
                    <ul>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                    </ul>
                    <div class="link">
                        <a href="#">sign up</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="pricingTable">
                <div class="pricing_heading">
                    <h3 class="title">Pricing Plan</h3>
                    <span class="value">
                        $12.99
                        <span class="month">per month</span>
                    </span>
                </div>
                <div class="content">
                    <ul>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                    </ul>
                    <div class="link">
                        <a href="#">sign up</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="pricingTable">
                <div class="pricing_heading">
                    <h3 class="title">Pricing Plan</h3>
                    <span class="value">
                        $22.99
                        <span class="month">per month</span>
                    </span>
                </div>
                <div class="content">
                    <ul>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                        <li>Lorem ipsum</li>
                    </ul>
                    <div class="link">
                        <a href="#">sign up</a>
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

export const PricingBlock3 = () => {
    return {
        id: "Pricing-block-3",
        class: "",
        category: "Pricing",
        label: "Pricing three",
        content: `
      <style>
      .pricingTable {
        text-align: center;
        background: #fff;
        margin: 15px; /* Set the margin to add gap between pricing tables */
        box-shadow: 0 0 10px #ababab;
        padding-bottom: 40px;
        border-radius: 10px;
        color: #cad0de;
        transform: scale(1);
        transition: all .5s ease 0s;
    }
    
    
    .pricingTable:hover {
        transform: scale(1.05);
        z-index: 1
    }
    
    .pricingTable .pricingTable-header {
        padding: 40px 0;
        background: #f5f6f9;
        border-radius: 10px 10px 50% 50%;
        transition: all .5s ease 0s
    }
    
    .pricingTable:hover .pricingTable-header {
        background: #ff9624
    }
    
    .pricingTable .pricingTable-header i {
        font-size: 50px;
        color: #858c9a;
        margin-bottom: 10px;
        transition: all .5s ease 0s
    }
    
    .pricingTable .price-value {
        font-size: 35px;
        color: #ff9624;
        transition: all .5s ease 0s
    }
    
    .pricingTable .month {
        display: block;
        font-size: 14px;
        color: #cad0de
    }
    
    .pricingTable:hover .month,
    .pricingTable:hover .price-value,
    .pricingTable:hover .pricingTable-header i {
        color: #fff
    }
    
    .pricingTable .heading {
        font-size: 24px;
        color: #ff9624;
        margin-bottom: 20px;
        text-transform: uppercase
    }
    
    .pricingTable .pricing-content ul {
        list-style: none;
        padding: 0;
        margin-bottom: 30px
    }
    
    .pricingTable .pricing-content ul li {
        line-height: 30px;
        color: #a7a8aa
    }
    
    .pricingTable .pricingTable-signup a {
        display: inline-block;
        font-size: 15px;
        color: #fff;
        padding: 10px 35px;
        border-radius: 20px;
        background: #ffa442;
        text-transform: uppercase;
        transition: all .3s ease 0s
    }
    
    .pricingTable .pricingTable-signup a:hover {
        box-shadow: 0 0 10px #ffa442
    }
    
    .pricingTable.blue .heading,
    .pricingTable.blue .price-value {
        color: #4b64ff
    }
    
    .pricingTable.blue .pricingTable-signup a,
    .pricingTable.blue:hover .pricingTable-header {
        background: #4b64ff
    }
    
    .pricingTable.blue .pricingTable-signup a:hover {
        box-shadow: 0 0 10px #4b64ff
    }
    
    .pricingTable.red .heading,
    .pricingTable.red .price-value {
        color: #ff4b4b
    }
    
    .pricingTable.red .pricingTable-signup a,
    .pricingTable.red:hover .pricingTable-header {
        background: #ff4b4b
    }
    
    .pricingTable.red .pricingTable-signup a:hover {
        box-shadow: 0 0 10px #ff4b4b
    }
    
    .pricingTable.green .heading,
    .pricingTable.green .price-value {
        color: #40c952
    }
    
    .pricingTable.green .pricingTable-signup a,
    .pricingTable.green:hover .pricingTable-header {
        background: #40c952
    }
    
    .pricingTable.green .pricingTable-signup a:hover {
        box-shadow: 0 0 10px #40c952
    }
    
    .pricingTable.blue:hover .price-value,
    .pricingTable.green:hover .price-value,
    .pricingTable.red:hover .price-value {
        color: #fff
    }
    
    @media screen and (max-width:990px) {
        .pricingTable {
            margin: 0 0 20px
        }
    }</style>
      <section data-id="pricing-block-3" data-type="html" data-group="statistic" class="wowsbar-block" data-gjs-editable="false" data-gjs-droppable="false">
      <div class="container"  data-gjs-editable="false" data-gjs-droppable="false">
          <div class="row  gx-2"  data-gjs-editable="false" data-gjs-droppable="false">
              <div class="col-md-3 col-sm-6"  data-gjs-droppable="false">
                  <div class="pricingTable">
                      <div class="pricingTable-header">
                          <i class="fa fa-adjust"></i>
                          <div class="price-value"> $10.00 <span class="month">per month</span> </div>
                      </div>
                      <h3 class="heading">Standard</h3>
                      <div class="pricing-content">
                          <ul>
                              <li><b>50GB</b> Disk Space</li>
                              <li><b>50</b> Email Accounts</li>
                              <li><b>50GB</b> Monthly Bandwidth</li>
                              <li><b>10</b> subdomains</li>
                              <li><b>15</b> Domains</li>
                          </ul>
                      </div>
                      <div class="pricingTable-signup">
                          <a href="#">sign up</a>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3 col-sm-6"  data-gjs-droppable="false">
                  <div class="pricingTable green">
                      <div class="pricingTable-header">
                          <i class="fa fa-briefcase"></i>
                          <div class="price-value"> $20.00 <span class="month">per month</span> </div>
                      </div>
                      <h3 class="heading">Business</h3>
                      <div class="pricing-content">
                          <ul>
                              <li><b>60GB</b> Disk Space</li>
                              <li><b>60</b> Email Accounts</li>
                              <li><b>60GB</b> Monthly Bandwidth</li>
                              <li><b>15</b> subdomains</li>
                              <li><b>20</b> Domains</li>
                          </ul>
                      </div>
                      <div class="pricingTable-signup">
                          <a href="#">sign up</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 col-sm-6"  data-gjs-droppable="false">
                  <div class="pricingTable blue">
                      <div class="pricingTable-header">
                          <i class="fa fa-diamond"></i>
                          <div class="price-value"> $30.00 <span class="month">per month</span> </div>
                      </div>
                      <h3 class="heading">Premium</h3>
                      <div class="pricing-content">
                          <ul>
                              <li><b>70GB</b> Disk Space</li>
                              <li><b>70</b> Email Accounts</li>
                              <li><b>70GB</b> Monthly Bandwidth</li>
                              <li><b>20</b> subdomains</li>
                              <li><b>25</b> Domains</li>
                          </ul>
                      </div>
                      <div class="pricingTable-signup">
                          <a href="#">sign up</a>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 col-sm-6"  data-gjs-droppable="false">
                  <div class="pricingTable red">
                      <div class="pricingTable-header">
                          <i class="fa fa-cube"></i>
                          <div class="price-value"> $40.00 <span class="month">per month</span> </div>
                      </div>
                      <h3 class="heading">Extra</h3>
                      <div class="pricing-content">
                          <ul>
                              <li><b>80GB</b> Disk Space</li>
                              <li><b>80</b> Email Accounts</li>
                              <li><b>80GB</b> Monthly Bandwidth</li>
                              <li><b>25</b> subdomains</li>
                              <li><b>30</b> Domains</li>
                          </ul>
                      </div>
                      <div class="pricingTable-signup">
                          <a href="#">sign up</a>
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

export const PricingBlock4 = () => {
    return {
        id: "Pricing-block-4",
        class: "",
        category: "Pricing",
        label: "Pricing four",
        content: `
    <style>
    .pricing-tables {
      background-color: #eeeeee;
      padding: 30px 0;
  }
  
  .single-table {
      background: #fff;
      transition: all 0.2s linear;
  }
  
  .single-table:hover {
      box-shadow: 0px 3px 3px 0px #888888;
  }
  
  .single-table .plan-header {
      background: #34495E;
      color: #fff;
      text-transform: capitalize;
      padding: 2px 0;
  }
  
  .single-table .plan-header h3 {
      margin: 0;
      padding: 20px 0 5px 0;
      text-transform: uppercase;
  }
  
  .single-table .plan-price {
      color: #fff;
      padding: 10px 0;
      margin: 0;
      font-size: 30px;
      border-top: 2px solid #587ca0;
      font-weight: bold;
  }
  
  .single-table .plan-price span {
      font-size: 18px;
      font-weight: normal;
  }
  
  .single-table ul {
      margin: 0;
      padding: 20px 0;
      list-style: none;
  }
  
  .single-table ul li {
      padding: 8px 0;
      margin: 0 20px;
      border-bottom: 1px solid #dae2ea;
      font-size: 15px;
  }
  
  .single-table .plan-submit {
      display: inline-block;
      text-decoration: none;
      margin: 20px 0 30px 0;
      padding: 10px 40px;
      border: 1px solid #34495E;
      color: #34495E;
      font-size: 15px;
      text-transform: uppercase;
  }
  
  .single-table .hvr-bounce-to-right:hover,
  .single-table .hvr-bounce-to-right:focus,
  .single-table .hvr-bounce-to-right:active {
      color: white;
  }
  
  .single-table .hvr-bounce-to-right:before {
      content: "";
      position: absolute;
      z-index: -1;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: #34495E;
      transform: scaleX(0);
      transform-origin: 0 50%;
      transition-property: transform;
      transition-duration: 0.5s;
      transition-timing-function: ease-out;
  }
  
  .single-table .hvr-bounce-to-right:hover:before,
  .single-table .hvr-bounce-to-right:focus:before,
  .single-table .hvr-bounce-to-right:active:before {
      transform: scaleX(1);
  }
  </style>
    <section data-id="pricing-block-4" data-type="html" data-group="statistic" class="wowsbar-block pricing-tables" data-gjs-editable="false" data-gjs-droppable="false">
    <div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="single-table text-center">
        <div class="plan-header">
            <h3>basic</h3>
            <p>plan for basic user</p>
            <h4 class="plan-price">$30<span>/month</span></h4>
        </div>


        <ul class="plan-features">
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
        </ul>
        <a href="#" class="plan-submit hvr-bounce-to-right">buy now</a>
    </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="single-table text-center">
        <div class="plan-header">
            <h3>basic</h3>
            <p>plan for basic user</p>
            <h4 class="plan-price">$30<span>/month</span></h4>
        </div>


        <ul class="plan-features">
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
        </ul>
        <a href="#" class="plan-submit hvr-bounce-to-right">buy now</a>
    </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="single-table text-center">
        <div class="plan-header">
            <h3>basic</h3>
            <p>plan for basic user</p>
            <h4 class="plan-price">$30<span>/month</span></h4>
        </div>


        <ul class="plan-features">
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
        </ul>
        <a href="#" class="plan-submit hvr-bounce-to-right">buy now</a>
    </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="single-table text-center">
        <div class="plan-header">
            <h3>basic</h3>
            <p>plan for basic user</p>
            <h4 class="plan-price">$30<span>/month</span></h4>
        </div>


        <ul class="plan-features">
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
            <li>10 Free PSD files</li>
        </ul>
        <a href="#" class="plan-submit hvr-bounce-to-right">buy now</a>
    </div>
        </div>
    </div>
    
</div>
</section>
`,
    };
};
