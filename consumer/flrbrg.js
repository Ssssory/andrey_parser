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

    const cooke = [
        {
            'name':'cf_clearance',
            'value':'s63H8mhfx_qg59yZ5738jzzxqG_i.ZWL9eos33kCJg4-1693657954-0-1-eb7c4845.1d53065c.47e9061e-0.2.1693657954',
        },
        {
            'name':'_pk_id.4.b299',
            'value':'5dd641b235ea7348.1693657955.',
        },
        {
            'name':'__cf_bm',
            'value': 'cU3jtBF9_hKVPifVVOjBDiSHeQbgNw1Af1EUyEbXqA0-1693841080-0-AUmEX/U6NBhoa83AfCbimWqMAaHNlz1uAW5VFo2woVXK6fZqJ9V2z80inCZyIMbXarQdwAAzaEAWyolTLpvc7bk='
        }
    ];
    await page.setViewport({ width: 1920, height: 1080 });
    
    // Navigate the page to a URL
    await page.goto('https://www.dextools.io/app/en/ether/pair-explorer/0x60914a03effbbcaf8f33d13d3dd51bd7b5d31f15');
    // page.setCookie(...cooke);

    // Set screen size

    // const responce = await page.waitForResponse(response => {}).then(()=>{
    //     console.log(page.cookies());
    // });
    await page.waitForTimeout(10000);

    
    try {
        // await page.waitForSelector('#cf-captcha-container');
        // const captcha = await page.$('#cf-captcha-container');
        // const solution = await captcha.solve();

        // Wait and click on first result
        const searchResultSelector = '.pool-info__list';
        const textSelector = await page.waitForSelector(searchResultSelector);

        const fullList = await textSelector?.evaluate(el => {
            console.log(el);
        });
    } catch (error) {
        fs.writeFile('contain.html', (await page.content()).toString(), err => {
            if (err) {
                console.error(err);
            }
            console.log('Successfully Written to File.');
        });
        await page.screenshot({
            path: 'screenshot.jpg'
        });
    }
    

    // Print the full title
    // console.log('The title of this blog post is "%s".', fullTitle);

    await browser.close();
})();