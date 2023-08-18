import { faker } from '@faker-js/faker';

const name = faker.person.fullName(); 
const email = faker.internet.email();
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
describe("register", () => {
    beforeEach(() => {
        cy.visit("http://wowsbar.test/register");
    });

    it("registers a user", () => {
        cy.get("#name").type(name);
        cy.get("#email").type(email);
        cy.get("#password").type("securepassword");
        cy.get("#password_confirmation").type("securepassword");
        cy.get("#show-password-password").click();
        cy.get("#show-password-password_confirmation").click();
        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/register");
        cy.intercept("GET", "http://wowsbar.test/dashboard", (req) => {
            cy.url("http://wowsbar.test/dashboard");
        });
    });

    it("prevents registration with mismatched passwords", () => {
        cy.get("#name").type("aiku");
        cy.get("#email").type("test@gmail.com");
        cy.get("#password").type("securepassword");
        cy.get("#password_confirmation").type("differentpassword"); 

        cy.get("#submit").click();
        cy.intercept("POST", "http://wowsbar.test/register");
        cy.intercept("GET", "http://wowsbar.test/register");
    });
});
