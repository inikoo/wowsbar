import data from "@/../../cypress/fixtures/example.json";

/// <reference types="cypress" />

const Users = () => {
    describe("Users", () => {
        beforeEach(() => {
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit(`http://aiku.wowsbar.test/sysadmin/users`);
        });

        it("Tabs Users", () => {
           cy.get('button[id*=users]').first().click()
           cy.url().should('eq','http://aiku.wowsbar.test/sysadmin/users?tab=users')
           tabsUsers()
        });


        it("Tabs Users Request", () => {
            cy.get('button[id*=users-requests]').first().click()
            cy.url().should('eq','http://aiku.wowsbar.test/sysadmin/users?tab=users_requests')
         });

    });
};

// const tabsUsers = () => {
//     cy.get("td")
//     .eq(1)
//     .then(($cell) => {
//         console.log('sssssss',$cell)
//         if ($cell[0].lastElementChild.tagName === "A") {
           
//         }
//     });

// }

export default Users;

Users();