import { faker } from "@faker-js/faker";
import data from "@/../../cypress/fixtures/example.json";
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

            //search
            // cy.get("input").type("test");

            // //filter
            // cy.get('button[aria-haspopup*=true]').click({ multiple : true })

            //table
            cy.get("td")
                .eq(0)
                .then(($cell) => {
                    if ($cell[0].lastElementChild.tagName === "A") {
                        cy.get($cell[0].lastElementChild).click();
                        console.log("sss", $cell[0].lastElementChild.id);
                        cy.url().should(
                            "eq",
                            `http://aiku.wowsbar.test/portfolio/websites/atop/banners/${$cell[0].lastElementChild.id}`
                        );
                    }
                });
        });
    });
};

export default Banner;

Banner();
