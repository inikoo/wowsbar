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

describe('register', () => {
    beforeEach(() => {
      cy.visit('http://wowsbar.test/register')
    })
  
    it('.type() - type into a DOM element', () => {
      cy.get('#name')
        .type('aiku').should('have.value', 'aiku')
  
        cy.get('#email')
        .type('hello').should('have.value', 'hello')

        cy.get('#password')
        .type('hello').should('have.value', 'hello')

        cy.get('#password_confirmation')
        .type('hello').should('have.value', 'hello')

    })
     
  })
  