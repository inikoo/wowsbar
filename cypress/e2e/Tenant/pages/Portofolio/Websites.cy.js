import { faker } from '@faker-js/faker'; // Check the correct import path
import data from '@/../../cypress/fixtures/data.json';
import Banner from '@/../../cypress/e2e/Tenant/pages/Portofolio/Banners.cy';

const code = faker.random.words({ min: 1, max: 1 });
const fakeName = faker.commerce.productName();
const fakeDomain = faker.internet.domainName();

const EditWebsite = (id) => {
    cy.get('#edit').click();
    cy.url().should("include", `/websites/${id}/edit`);
    cy.get('#domain').clear().type(fakeDomain);
    cy.get('button[type="submit"]').not('[disabled]').click({ multiple: true });
    cy.get('#code').clear().type(faker.random.words({ min: 1, max: 1 }));
    cy.get('button[type="submit"]').not('[disabled]').click({ multiple: true });
    cy.get('#name').clear().type(fakeName);
    cy.get('button[type="submit"]').not('[disabled]').click({ multiple: true });
};

function websites(){
    describe("Websites", () => {
        beforeEach(() => {
            cy.setCookie(data.cookieName, data.cookieSession);
            cy.visit("http://aiku.wowsbar.test/portfolio/websites");
        });

        it("website create", () => {
            cy.get('#create-website').click();
            cy.url().should("include", "http://aiku.wowsbar.test/portfolio/websites/create");
            cy.go('back');
            cy.go('forward');
            cy.get('#domain').type(fakeDomain);
            cy.get('#code').type(code);
            cy.get('#name').type(fakeName);
            cy.get('button[type="submit"]').click();
        });

        it("website create wrong", () => {
            cy.get('#create-website').click();
            cy.url().should("include", "http://aiku.wowsbar.test/portfolio/websites/create");
            cy.go('back');
            cy.go('forward');
            cy.get('#domain').clear();
            cy.get('#code').type(faker.random.words({ min: 8, max: 8 }));
            cy.get('#name').type(fakeName);
            cy.get('button[type="submit"]').click();
        });

        it("website table navigation", () => {
            cy.get("td").first().then(($cell) => {
                if ($cell[0].lastElementChild.tagName === 'A') {
                    cy.get($cell[0].lastElementChild).click();
                    cy.url().should('include', `/portfolio/websites/${$cell[0].lastElementChild.id}`);
                    EditWebsite($cell[0].lastElementChild.id);
                }
            });
        });
    });
};

export default websites();
