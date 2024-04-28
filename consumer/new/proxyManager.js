const fs = require('fs');

class ProxyManager {
    constructor(proxyFilePath) {
        this.proxies = JSON.parse(fs.readFileSync(proxyFilePath, 'utf8'));
    }

    getRandomProxy() {
        return this.proxies[Math.floor(Math.random() * this.proxies.length)];
    }
}

module.exports = ProxyManager;