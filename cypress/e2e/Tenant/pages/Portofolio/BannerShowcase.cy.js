import { faker } from "@faker-js/faker";
import data from "@/../../cypress/fixtures/example.json";
const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
/// <reference types="cypress" />

const BannerShowcase = (param = { websites : 'hello', banner: 'test1'}) => {
    describe("Banner Showcae", () => {
        beforeEach(() => {
            // Set up: Login and visit the banners section
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/portfolio/websites/${param.websites}/banners/${param.banner}`);
            
        });

        it("banner button", () => {
            cy.get("#autoplay").click();
            cy.get("#next").click();
            cy.get("#previous").click();
        });
    });
};

export default BannerShowcase;

BannerShowcase();
