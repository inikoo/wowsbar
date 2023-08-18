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

describe("login", () => {
    beforeEach(() => {
        cy.visit("http://aiku.wowsbar.test/login");
    });

    it("login", () => {
        cy.get("#username").type("aiku");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();

        cy.intercept("POST", "http://aiku.wowsbar.test/login");
        cy.intercept("GET", "http://aiku.wowsbar.test/dashboard", (req) => {
            cy.url().should("eq", "http://aiku.wowsbar.test/dashboard");
        });
    });

    it("false password", () => {
        cy.get("#username").type("aiku");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();

        cy.intercept("POST", "http://aiku.wowsbar.test/login");
        cy.intercept("GET", "http://aiku.wowsbar.test/login");
    });

    it("false password", () => {
        cy.get("#username").type("aiku123");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();

        cy.intercept("POST", "http://aiku.wowsbar.test/login");
        cy.intercept("GET", "http://aiku.wowsbar.test/login");
    });
});
