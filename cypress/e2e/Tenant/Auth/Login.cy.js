/// <reference types="cypress" />

describe("login", () => {
    beforeEach(() => {
        cy.visit("http://aiku.wowsbar.test/login");
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

    it("login", () => {
        cy.intercept("POST", "http://aiku.wowsbar.test/login").as("loginRequest");

        cy.get("#username").type("aiku");
        cy.get("#password").type("hello");
        cy.get("#showPassword").click();
        cy.get("#remember-me").click();
        cy.get("#submit").click();

        // Wait for the login request to complete
        cy.wait("@loginRequest").then(() => {
            cy.getCookie("wowsbar_session")
        });
    });
});
