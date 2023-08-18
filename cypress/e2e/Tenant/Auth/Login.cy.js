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

describe('login', () => {
  beforeEach(() => {
    cy.visit('http://aiku.wowsbar.test/login')
  })

  
  it('.type() - type into a DOM element', () => {
    cy.get('#username')
      .type('aiku').should('have.value', 'aiku')

      cy.get('#password')
      .type('hello').should('have.value', 'hello')

      cy.get('#showPassword').click()

      cy.get('#remember-me').click()

      cy.get('#submit').click()
  })
   
})
