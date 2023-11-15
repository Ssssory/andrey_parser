const puppeteer = require('puppeteer');

(async () => {
    // Launch the browser and open a new blank page
    const browser = await puppeteer.launch({
        executablePath: '/usr/bin/google-chrome',
        // args: [...] // if we need them.
    });
    const page = await browser.newPage();

    // Navigate the page to a URL
    await page.goto('https://www.google.com/');

    // Set screen size
    await page.setViewport({ width: 1920, height: 1080 });

    await page.screenshot({
        path: 'screenshot.jpg'
    });

    // // Type into search box
    // await page.type('.search-box__input', 'automate beyond recorder');

    // // Wait and click on first result
    // const searchResultSelector = '.search-box__link';
    // await page.waitForSelector(searchResultSelector);
    // await page.click(searchResultSelector);

    // // Locate the full title with a unique string
    // const textSelector = await page.waitForSelector(
    //     'text/Customize and automate'
    // );
    // const fullTitle = await textSelector?.evaluate(el => el.textContent);

    // // Print the full title
    // console.log('The title of this blog post is "%s".', fullTitle);

    await browser.close();
})();