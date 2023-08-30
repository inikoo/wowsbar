import { faker } from "@faker-js/faker";
import data from '@/../../cypress/fixtures/data.json'
import { BannerShowcase } from "@/../../cypress/e2e/Tenant/pages/Portofolio/BannerShowcase.cy";
const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
const domain = faker.internet.url()
/// <reference types="cypress" />

const Banner = (id = "hello") => {
    describe("Website Banners", () => {
        beforeEach(() => {
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/portfolio/websites/${id}`);
            cy.get("#banners").click();
        });

    
        it("creates a new banner", () => {
            cy.get("#new-banner").click();
            cy.get("#code").type(code);
            cy.get("#name").type(fakeName);
            cy.get('button[type="submit"]').click();
            cy.url().should("include", "banners/create");
        });

        // it("fails to create a banner with wrong data", () => {
        //     cy.get("#new-banner").click();

        //     cy.get("#code").clear();
        //     cy.get("#name").clear();
        //     cy.get('button[type="submit"]').click();

        //     cy.url().should("include", "banners/create");
        // });

        it("websites", () => {
            cy.get("#website").click();
            // cy.url().should("include", `http://aiku.wowsbar.test/portfolio/websites/${id}?tab=showcase`);
        });

        it("banners", () => {
            
            cy.get("button[id*=banners]").click();
            // cy.url().should("include", `http://aiku.wowsbar.test/portfolio/websites/${id}?tab=showcase`);

            SearchTable(id);
            // FilterColums(id);
            // filterByStatus(id);
            Table();
        });
    });
};

const filterByStatus = (id) => {
    cy.get("div[role*=filter]").each((role) => {
        cy.get(role[0]).click();
        const filterData = ["In-Process", "Ready", "Live", "Retired"];
        const data = filterData.filter((item) => item !== role[0].id);
        const lowercaseData = data.map((item) => item.toLowerCase());
        const url = `http://aiku.wowsbar.test/portfolio/websites/hello?tab=banners&banners_elements[state]=${lowercaseData.join()}&banners_sort=slug`;
        cy.url().should("includes", url);
    });
};

const SearchTable = (id) => {
    cy.get("input").type("azure{enter}");
    cy.intercept(
        "GET",
        `http://aiku.wowsbar.test/portfolio/websites/${id}`,
        (req) => {
            req.body = {
                tab: "banners",
                banners_filter: "azure",
            };
        }
    ).as("interceptedRequest");
};

const FilterColums = (id) => {
    cy.get("#filter-colums")
        .click({ multiple: true })
        .then(() => {
            cy.get("li").each((test) => {
                if (test[0].lastChild.id) cy.get(test[0].lastChild).click();
                const data = ["code", "name", "banner"];
                const index = data.findIndex(
                    (item) => item == test[0].lastChild.id
                );
                data.splice(index, 1);
                cy.intercept(
                    "GET",
                    `http://aiku.wowsbar.test/portfolio/websites/hello?tab=banners&banners_columns=${data.toString()}&banners_sort=slug`
                );
            });
        });
};

const Table = (id) => {
    cy.get("td")
        .eq(0)
        .then(($cell) => {
            if ($cell[0].lastElementChild.tagName === "A") {
                cy.get($cell[0].lastElementChild).click();
                // cy.url().should(
                //     "includes",
                //     `http://aiku.wowsbar.test/portfolio/websites/${id}/banners/${$cell[0].lastElementChild.id}`
                // );
                // BannerShowcase({
                //     websites: id,
                //     banner: $cell[0].lastElementChild.id,
                // });
            }
        });
};

export default Banner();


