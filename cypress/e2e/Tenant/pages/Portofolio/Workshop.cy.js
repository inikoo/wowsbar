
import data from '@/../../cypress/fixtures/data.json'
import { duration } from 'moment'

/// <reference types="cypress" />

function importImage(){
    cy.get('button[id*=gallery]').click()
        cy.get('#stock_images').click()
        cy.get('#5').click()
        cy.get('#2').click()
        cy.get('#6').click()
        cy.get('#add-image').click()
}

function duration(){
    cy.get('button[id*=gallery]').click()
}

describe("Workshop", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/portfolio/banners/test1/workshop");
    });

    it("import Image", () => {
        importImage()
        });


        it("Common", () => {
            importImage()
            cy.get('li[id*=tab-nav]').each((e)=>{
                cy.get(e).click()
                if(e[0].outerText == 'Duration'){
                    duration()
                }
            })
            });
        
    });

