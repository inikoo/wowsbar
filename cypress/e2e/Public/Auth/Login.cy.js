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
        cy.visit("http://wowsbar.test/login");
    });

    it("false password", () => {
        cy.get("#email").type("test2@gmail.com");
        cy.get("#password").type("123");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/login")
        cy.intercept("GET", "http://wowsbar.test/login");
    });

    it("false email", () => {
        cy.get("#email").type("tesddt2@gmail.com");
        cy.get("#password").type("securepassword");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/login")
        cy.intercept("GET", "http://wowsbar.test/login")
    });

    it("login", () => {
        cy.get("#email").type("test2@gmail.com");
        cy.get("#password").type("securepassword");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/login")
        cy.intercept("GET", "http://wowsbar.test/dashboard", (req) => {
            cy.url("http://wowsbar.test/dashboard");
        });
    });
});
