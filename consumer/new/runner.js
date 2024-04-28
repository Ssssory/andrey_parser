require('module-alias/register');
const puppeteer = require('puppeteer');
const ProxyManager = require('./proxyManager');

const useProxy = process.env.USE_PROXY === 'true';
const proxyManager = new ProxyManager('./proxies.json');

const HalooglasiHomapage = require('@halooglasi/homepage');

const strategies = {
    'https://www.halooglasi.com/': HalooglasiHomapage,
    'https://2ip.io/': TwoIpHomepage
};

(async () => {
    let launchOptions = {
        headless: true,
        defaultViewport: null,
        userDataDir: './tmp',
        executablePath: '/usr/bin/google-chrome',
        args: ['--no-sandbox', '--disable-setuid-sandbox'] // Флаги безопасности
    };

    if (useProxy) {
        const proxy = proxyManager.getRandomProxy();
        launchOptions.args.push(`--proxy-server=${proxy.ip}:${proxy.port}`);
    }

    const browser = await puppeteer.launch(launchOptions);

    for (const [url, Strategy] of Object.entries(strategies)) {
        const page = await browser.newPage();

        // if (!useProxy) {
            const strategy = new Strategy(page, url);
            const cachedContent = strategy.loadCache();

            if (cachedContent) {
                await page.setContent(cachedContent);
            } else {
                await page.goto(url);
                await strategy.saveCache();
            }
        // } else {
        //     await page.goto(url);
        // }

        // const strategy = new Strategy(page, url);
        await strategy.recapcha();
        await strategy.test();
        await page.close();
    }

    await browser.close();
})();