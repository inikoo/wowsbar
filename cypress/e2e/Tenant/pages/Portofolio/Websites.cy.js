
import { faker } from '@faker-js/faker';
import data from '@/../../cypress/fixtures/example.json'
import Banner from '@/../../cypress/e2e/Tenant/pages/Portofolio/Banners.cy'
const code = faker.random.words({ min: 1, max: 1 })
const fakeName = faker.commerce.productName();
const fakeDomain = faker.internet.domainName();
/// <reference types="cypress" />


const websites = (websites = 'hello') => {
  describe("Websites", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/portfolio/websites");
    });

    it("website table navigation", () => {
        cy.get("td").eq(0).then(($cell) => { // Use .then() instead of (.)
          if ($cell[0].lastElementChild.tagName === 'A') {
            cy.get($cell[0].lastElementChild).click();
            cy.url().should('eq', `http://aiku.wowsbar.test/portfolio/websites/${$cell[0].lastElementChild.id}`);
            Banner($cell[0].lastElementChild.id)
          }
        });
      });



    it("website create", () => {
        cy.get('#create-website').click();
        cy.url().should("include", "/websites/create");
        cy.go('back')
        cy.go('forward')
        cy.get('#domain').type(fakeDomain)
        cy.get('#code').type(code)
        cy.get('#name').type(fakeName)
        cy.get('button[type*=submit]').click()
    });


    it("website create wrong", () => {
        cy.get('#create-website').click();
        cy.url().should("include", "/websites/create");
        cy.go('back')
        cy.go('forward')
        cy.get('#domain').clear();
        cy.get('#code').type(faker.random.words({ min: 8, max: 8 }))
        cy.get('#name').type(fakeName)
        cy.get('button[type*=submit]').click()
    });


  

});
}

export default websites

websites()