import { faker } from '@faker-js/faker';
import data from '@/../../cypress/fixtures/data.json'
const about = faker.lorem.paragraph()
const email = faker.internet.email();


/// <reference types="cypress" />


describe("Navigation", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/profile");
    });

      it("Profile", () => {
        cy.get('li').eq(2).click();
        cy.get("#email").clear().type(email);
        cy.get('button[type="submit"]').not('[disabled]').click();
        cy.get("#about").clear().type(about);
        cy.get('button[type="submit"]').not('[disabled]').click();
    });

    it("Password", () => {
        cy.get('li').eq(3).click();
        cy.get("#password").clear().type('hello');
        cy.get("#show-password-password").click();
        cy.get('button[type="submit"]').not('[disabled]').click();
    });

    it("Language", () => {
        cy.get('li').eq(4).click();
        cy.get(".multiselect-search").click()
        cy.get(".multiselect-option").eq(1).click()
        cy.get('button[type="submit"]').not('[disabled]').click();
        cy.reload();
    });

    
});
