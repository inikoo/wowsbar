import data from "@/../../cypress/fixtures/example.json";

/// <reference types="cypress" />

describe("Gallery", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName, data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/portfolio/gallery");
    });

    it("Upload Images", () => {
        cy.get("#uploaded-images").click();
        // cy.get("#uploadFile").click();

    });

    it("Stock Images", () => {
        cy.get("#stock-images").click();
    });


    it("select images create", () => {
        cy.get("#stock-images").click();
        cy.get("#select-images").click();
        cy.get('input[id*=select-image]').first().click()
        cy.get('#create-banner').click()
    });


    it("select images failed", () => {
        cy.get("#stock-images").click();
        cy.get("#select-images").click();
        cy.get('input[id*=select-image]').first().click()
        cy.get('#cancel-select').click()
    });
});
