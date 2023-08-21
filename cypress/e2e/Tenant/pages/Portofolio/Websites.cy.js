
import { faker } from '@faker-js/faker';
import data from '@/../../cypress/fixtures/example.json'
import { eq } from 'lodash';
const code = faker.random.words({ min: 1, max: 1 })
const fakeName = faker.commerce.productName();
const fakeDomain = faker.internet.domainName();
/// <reference types="cypress" />

// Welcome to Cypress!
//
// This spec file contains a variety of sample tests
// for a todo list app that are designed to demonstrate
// the power of writing tests in Cypress.
//
// To learn more about how Cypress works and
// what makes it such an awesome testing tool,
// please read our getting started guide:
// https://on.cypress.io/introduction-to-cypress

describe("Websites", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/portfolio/websites");
    });

    it("website navigation", () => {
        cy.get('a[href*="/websites"]').eq(0).click()
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
