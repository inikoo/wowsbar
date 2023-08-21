import { faker } from "@faker-js/faker";
import data from "@/../../cypress/fixtures/example.json";
const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
/// <reference types="cypress" />

const BannerShowcase = (id = "atop") => {
    describe("Website Banners", () => {
        beforeEach(() => {
            // Set up: Login and visit the banners section
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/portfolio/websites/${id}`);
            cy.get("#banners").click();
        });
    });
};

export default BannerShowcase;

BannerShowcase();
