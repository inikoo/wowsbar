import { faker } from '@faker-js/faker';

const about = faker.lorem.paragraph()
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

describe("Navigation", () => {
    beforeEach(() => {
        cy.setCookie("wowsbar_session", "eyJpdiI6InRqYnJZWGo4L2RwaTZYd0R0ZDdIckE9PSIsInZhbHVlIjoiSVQyMVRxL003cUN3eWFLS2NRaHZxYi8rb0J4U0N4MW1EYmV3WmJXR3VUY3YyemkrNzJaYnZlTHdzbHhHV1ZWYTV4bDl4VkdRcVNDTjRmdExndmlsU0hFc0pjTG9RYWlibWhhcWYxT3U4ZXpnM1BlcnpPbnlMRUV5ZnQzdXJLWUYiLCJtYWMiOiI5ZWUxYmM2Mjc4N2FmNjhkNjgyODJkZWM2ZjhkYzU5NjU1Y2M5M2Y3NTJkYmZmMmVkNTg0MTk4ZjQ2NGJkMmMxIiwidGFnIjoiIn0%3D%3D%3D%3D%3D%3D%3D%3D%3D%3D%3D");
        cy.visit("http://aiku.wowsbar.test/profile");
    });

      it("Profile", () => {
        cy.get('li').eq(2).click();
        cy.get("#email").clear().type(email);
        cy.get('button[type="submit"]').not('[disabled]').click();
        cy.get("#about").clear().type(about);
        cy.get('button[type="submit"]').not('[disabled]').click();
    });

    it("Password", () => {
        cy.get('li').eq(3).click();
        cy.get("#password").clear().type('hello');
        cy.get("#show-password-password").click();
        cy.get('button[type="submit"]').not('[disabled]').click();
    });

    it("Language", () => {
        cy.get('li').eq(4).click();
        cy.get(".multiselect-search").click()
        cy.get(".multiselect-option").eq(1).click()
        cy.get('button[type="submit"]').not('[disabled]').click();
        cy.reload();
    });

    
});
