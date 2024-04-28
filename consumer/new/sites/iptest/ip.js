require('module-alias/register');

const Page = require('@pages/page');

class TwoIpHomepage extends Page {
    async test() {
        console.log('Testing Home Page');
        // Реализуйте проверки для главной страницы
    }
}

module.exports = TwoIpHomepage;