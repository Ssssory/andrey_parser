const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth')
const fs = require('fs');

puppeteer.use(StealthPlugin());


(async () => {
    // Launch the browser and open a new blank page
    const browser = await puppeteer.launch({
        headless: 'new',
        executablePath: '/usr/bin/google-chrome',
        args: ['--no-sandbox']
    });
    const page = await browser.newPage();

    page.setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/94.0.4606.81 Safari/537.36');

    // const cooke = [
    //     {
    //         'name': 'cf_clearance',
    //         'value': 's63H8mhfx_qg59yZ5738jzzxqG_i.ZWL9eos33kCJg4-1693657954-0-1-eb7c4845.1d53065c.47e9061e-0.2.1693657954',
    //     },
    // ];
    await page.setViewport({ width: 1920, height: 1080 });

    // Navigate the page to a URL
    await page.goto('https://www.halooglasi.com/nekretnine/izdavanje-stanova/izdaje-se-renovirani-3-0-stan-na-staroj-karab/5425642455044?kid=4&sid=1708270392798');
    // page.setCookie(...cooke);

    // await page.waitForTimeout(10000);

    try {
        const searchH1Selector = 'h1.product-details-title';
        const h1Selector = await page.waitForSelector(searchH1Selector);
    } catch (error) {
            fs.writeFile('/srv/app/halooglasi/contain.html', (await page.content()).toString(), err => {
                if (err) {
                    console.error(err);
                }
                console.log('Successfully Written to File.');
            });
            await page.screenshot({
                path: '/srv/app/halooglasi/problem.jpg'
            });
    }

    await browser.close();
})();