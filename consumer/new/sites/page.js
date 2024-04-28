const path = require('path');
const fs = require('fs');

class Page {
    constructor(page, url) {
        this.page = page;
        this.url = url;
        this.cacheDir = path.join(__dirname, 'cache');
        this.screenshotPath = path.join(this.cacheDir, `${encodeURIComponent(url)}.png`);
        this.htmlPath = path.join(this.cacheDir, `${encodeURIComponent(url)}.html`);
    }
    
    async saveCache() {
        const html = await this.page.content();
        fs.writeFileSync(this.htmlPath, html);
    }

    loadCache() {
        if (fs.existsSync(this.screenshotPath) && fs.existsSync(this.htmlPath)) {
            console.log(`Loading from cache for ${this.pageName}`);
            return fs.readFileSync(this.htmlPath, 'utf8');
        }
        return null;
    }

    async saveScreenshot() {
        await this.page.screenshot({ path: this.screenshotPath });
    }

    async test() {
        throw new Error('Method "test" should be implemented');
    }
}

module.exports = Page;