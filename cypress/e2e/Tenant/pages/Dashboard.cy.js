
import data from '@/../../cypress/fixtures/example.json'
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

describe("Navigation", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/dashboard");
    });


    it("Navigation portofolio", () => {
        cy.get('a[href*="/portfolio/dashboard"]').click();
        cy.url().should("include", "/portfolio/dashboard");
        cy.go('back')
        cy.go('forward')
    });

    it("Navigation Sysadmin", () => {
        cy.get('a[href*="/sysadmin"]').click();
        cy.url().should("include", "/sysadmin");
        cy.go('back')
        cy.go('forward')
    });


    // it("Navigation Dashboard", () => {
    //     cy.visit("http://aiku.wowsbar.test/portfolio/dashboard");
    //     cy.get('a[href*="/dashboard"]').click();
    //     cy.url().should("include", "/dashboard");
    // });

      it("Profile", () => {
        cy.get('#avatar-thumbnail').click();
        cy.get('ul[role*=menuitem]').first().click();
        cy.url().should("include", "/profile");
    });


    it("search", () => {
        cy.get('#search').click();
        cy.get('input[type*=text]').type('My{enter}');
        cy.intercept('http://aiku.wowsbar.test/search/?q=My%20&route_src=dashboard.show')
    });


    // it("logout", () => {
    //     cy.get('#avatar-thumbnail').click();
    //     cy.get('ul[role*=menuitem]').last().click();
    //     cy.intercept("POST", "http://aiku.wowsbar.test/logout");
    //     cy.intercept("get", "http://aiku.wowsbar.test/login");
    //     cy.url().should("include", "/login");
    // });
    

    
});
