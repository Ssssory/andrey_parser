require('module-alias/register');

const Page = require('@pages/page');

class HomePageTest extends Page {
    async test() {
        console.log('Testing Home Page');
        // Реализуйте проверки для главной страницы
    }

    async recapcha() {
        console.log('Recapcha');
        // Реализуйте обход Recapcha
    }
}

module.exports = HomePageTest;