
import data from '@/../../cypress/fixtures/example.json'

/// <reference types="cypress" />

// Welcome to Cypress!
//
// This spec file contains a variety of sample tests
// for a todo list app that are designed to demonstrateW
// the power of writing tests in Cypress.
//
// To learn more about how Cypress works and
// what makes it such an awesome testing tool,
// please read our getting started guide:
// https://on.cypress.io/introduction-to-cypress

describe("Portofolio", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/portfolio/dashboard");
    });

    it("Navigation portofolio", () => {
        cy.get('a.group').each(($link) => {
            if($link[0].id){
                cy.wrap($link).click();
            
                if(!$link[0].id.includes('.')) cy.url().should('include', `http://aiku.wowsbar.test/portfolio/${$link[0].id}`);
            } 
          });
        });
        
    });

