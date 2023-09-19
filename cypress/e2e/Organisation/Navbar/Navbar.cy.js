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
        cy.visit("http://wowsbar.test/dashboard");
    });

    it("login", () => {
        cy.get("#username").type("aiku");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/org/login");
        cy.intercept("GET", "http://wowsbar.test/org/dashboard", (req) => {
            cy.url().should("eq", "http://wowsbar.test/org/dashboard");
        });
    });

    it("false username", () => {
        cy.get("#username").type("aiku ddd");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/org/login");
        cy.intercept("GET", "http://wowsbar.test/org/login");
    });

    it("false password", () => {
        cy.get("#username").type("aiku");
        cy.get("#password").type("hello123");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/org/login");
        cy.intercept("GET", "http://wowsbar.test/org/login");
    });
});
