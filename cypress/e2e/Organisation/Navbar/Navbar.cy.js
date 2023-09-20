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

    it("navigation", () => {
        cy.get("#username").type("aiku");
    });
});
