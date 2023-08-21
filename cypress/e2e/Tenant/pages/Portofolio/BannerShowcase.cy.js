import { faker } from "@faker-js/faker";
import data from "@/../../cypress/fixtures/example.json";
const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
/// <reference types="cypress" />

const BannerShowcase = (param = { websites : 'hello', banner: 'azure'}) => {
    describe("Banner Showcae", () => {
        beforeEach(() => {
            // Set up: Login and visit the banners section
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/portfolio/websites/${param.websites}/banners/${param.banner}`);
            cy.get("#banners").click();
        });

        it("creates a new banner", () => {
            // Click on "New Banner"
           
        });
    });
};

export default BannerShowcase;

BannerShowcase();
