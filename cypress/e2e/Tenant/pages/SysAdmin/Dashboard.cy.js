
import data from '@/../../cypress/fixtures/example.json'
/// <reference types="cypress" />



describe("Navigation", () => {
    beforeEach(() => {
        cy.setCookie(data.cookieName,data.cookieSession);
        cy.visit("http://aiku.wowsbar.test/sysadmin/users");
    });


    it("Navigation SysAdmin", () => {
        cy.get('a.group').each(($link) => {
            console.log($link)
            if($link[0].id){
                cy.wrap($link).click();
            
                if(!$link[0].id.includes('.')) cy.url().should('include', `http://aiku.wowsbar.test/sysadmin/${$link[0].id.replace(' ','-')}`);
            } 
          });
        });
        
    });   

