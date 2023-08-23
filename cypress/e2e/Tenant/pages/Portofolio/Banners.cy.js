import { faker } from "@faker-js/faker";
import data from "@/../../cypress/fixtures/example.json";
import BannerShowcase from "@/../../cypress/e2e/Tenant/pages/Portofolio/BannerShowcase.cy";
const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
/// <reference types="cypress" />

const Banner = (id = "hello") => {
    describe("Website Banners", () => {
        beforeEach(() => {
            // Set up: Login and visit the banners section
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/portfolio/websites/${id}`);
            cy.get("#banners").click();
        });

        it("Edit Website", () => {
            cy.get('#edit').click();
            cy.url().should("include", `/websites/${id}/edit`);
            cy.go('back')
            cy.go('forward')
            cy.get('#domain').clear();
            cy.get('button[type="submit"]').not('[disabled]').click();
            cy.get('#code').type(faker.random.words({ min: 1, max: 1 }))
            cy.get('button[type="submit"]').not('[disabled]').click();
            cy.get('#name').type(fakeName)
            cy.get('button[type="submit"]').not('[disabled]').click();
        });
        


        it("creates a new banner", () => {
            // Click on "New Banner"
            cy.get("#new-banner").click();
            // Fill in banner details
            cy.get("#code").type(code);
            cy.get("#name").type(fakeName);
            // Submit the form
            cy.get('button[type="submit"]').click();
            // Assert the URL after submitting
            cy.url().should("include", "banners/create");
        });

        it("fails to create a banner with wrong data", () => {
            cy.get("#new-banner").click();

            // Clear fields and submit
            cy.get("#code").clear();
            cy.get("#name").clear();
            cy.get('button[type="submit"]').click();

            // Assert the URL after submitting (should stay on the create banner page)
            cy.url().should("include", "banners/create");
        });

        it("websites", () => {
            // Click on the link of the first banner in the list
            cy.get("#website").click();

            // Assert the URL after clicking the banner link
            cy.url().should("include", "?tab=showcase");
        });

        it("banners", () => {
            // Click on the link of the first banner in the list
            cy.get("button[id*=banners]").click();
            cy.url().should("include", "?tab=banners");

            // search
            SearchTable(id);

            // filter colums
            FilterColums(id);

            filterByStatus(id);

            // table
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
        cy.url().should("eq", url);
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
                cy.url().should(
                    "eq",
                    `http://aiku.wowsbar.test/portfolio/websites/${id}/banners/${$cell[0].lastElementChild.id}`
                );
                BannerShowcase({
                    websites: id,
                    banner: $cell[0].lastElementChild.id,
                });
            }
        });
};

export default Banner;

Banner();
