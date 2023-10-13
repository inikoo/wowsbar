export const CtaBlock1 = () => {
    return {
        disable: false,
        activate: false,
        category: "CTA",
        label: `CTA One`,
        id: "cta-block-1",
        content: `
        <style>
        .cta {
          background: linear-gradient(rgba(40, 58, 90, 0.9), rgba(40, 58, 90, 0.9)), url("../img/cta-bg.jpg") fixed center center;
          background-size: cover;
          padding: 120px 0;
        }
        
        .cta h3 {
          color: #fff;
          font-size: 28px;
          font-weight: 700;
        }
        
        .cta p {
          color: #fff;
        }
        
        .cta .cta-btn {
          font-family: "Jost", sans-serif;
          font-weight: 500;
          font-size: 16px;
          letter-spacing: 1px;
          display: inline-block;
          padding: 12px 40px;
          border-radius: 50px;
          transition: 0.5s;
          margin: 10px;
          border: 2px solid #fff;
          color: #fff;
        }
        
        .cta .cta-btn:hover {
          background: #47b2e4;
          border: 2px solid #47b2e4;
        }
        
        @media (max-width: 1024px) {
          .cta {
            background-attachment: scroll;
          }
        }
        
        @media (min-width: 769px) {
          .cta .cta-btn-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
          }
        }
        </style>
        <section data-id="cta-block-4" data-type="html" data-group="cta" class="wowsbar-block cta" data-gjs-editable="false" data-gjs-droppable="false">
        <div class="container" data-aos="zoom-in">
  
          <div class="row">
            <div class="col-lg-9 text-center text-lg-start">
              <h3>Call To Action</h3>
              <p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="col-lg-3 cta-btn-container text-center">
              <a class="cta-btn align-middle" href="#">Call To Action</a>
            </div>
          </div>
  
        </div>
   </section>
`,
    };
};

export const CtaBlock2 = () => {
  return {
      disable: false,
      activate: false,
      category: "CTA",
      label: `CTA Two`,
      id: "cta-block-2",
      content: `
      <style>
      * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
    }
    .img-fluid{
      height:295px;
    }
    #iv2x{
      background:#754ffe;
    }
    #ixsr{
      padding:20px;
    }
    .text-white.fw-bold.pe-lg-8{
      font-size:39px;
    }
    .text-white-50.mb-4.lead{
      color:#a4a4a4;
    }
    .container{
      color:#fffbfb;
    }
    #iieen{
      color:#d1d1d1;
    }
      </style>
      <section data-id="cta-block-4" data-type="html" data-group="cta" class="wowsbar-block" id="iv2x">
      <div class="container" id="ixsr">
        <!-- row -->
        <div class="row align-items-center g-0">
          <div class="col-xl-6 col-lg-6 col-md-12">
            <!-- heading -->
            <div>
              <h1 class="text-white fw-bold pe-lg-8">Join the Geeks team &amp; shape the future of design
              </h1>
              <!-- text -->
              <p class="text-white-50 mb-4 lead">
                <span id="iieen">If you’re passionate and ready to dive in, we’d love to meet you. We’re committed to supporting our employee professional development and well-being.</span>
              </p>
              <!-- btn -->
              <a href="#" class="btn btn-dark">View opportunities</a>
            </div>
          </div>
          <!-- img -->
          <div class="col-xl-6 col-lg-6 col-md-12 d-flex justify-content-end pt-6">
          <img src="https://dummyimage.com/358x295" data-gjs-droppable="false" class="img-fluid" alt="Description of the image">
      </div>
        </div>
      </div>
    </section>
`,
  };
};


export const CtaBlock3 = () => {
  return {
      disable: false,
      activate: false,
      category: "CTA",
      label: `CTA three`,
      id: "cta-block-3",
      content: `
      <style>
      .text-white.fw-bold.pe-lg-8{
        font-size:39px;
      }
      .text-white-50.mb-4.lead{
        color:rgb(164, 164, 164);
      }
      .container{
        color:rgb(255, 251, 251);
      }
      .lead.px-lg-12.mb-6{
        color:#64748b;
        font-size:18px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:300;
      }
      .display-3.mt-4.mb-3.fw-bold{
        color:black;
        font-size:49px;
      }
      .lead.px-lg-8.mb-6{
        color:#64748b;
        font-weight:500;
      }
      </style>
      <section data-id="cta-block-4" data-type="html" data-group="cta" class="wowsbar-block py-14">
          <div class="container">
            <div class="row">
              <div class="offset-lg-2 col-lg-8 col-md-12 col-12 text-center">
                <span class="fs-4 text-warning ls-md text-uppercase fw-semibold">get things done
                </span>
                <!-- heading  -->
                <h2 class="display-3 mt-4 mb-3 fw-bold">Just try it out! You’ll
                  fall in love
                </h2>
                <!-- para  -->
                <p class="lead px-lg-8 mb-6">Designed for modern
                  companies looking to launch
                  a simple, premium and modern website and apps.
                </p>
                <a href="#" class="btn btn-primary">Contact us</a>
              </div>
            </div>
          </div>
      </section>
`,
  };
};


