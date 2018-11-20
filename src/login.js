const { detect } = require('detect-browser');
const browser = detect();

// handle the case where we don't detect the browser
switch (browser && browser.name) {
    case 'chrome':
    case 'safari':
    case 'firefox':
    case 'edge':
        break;
    default:
        alert(browser.name + " not support , please using chrome, safari, firefox or edge.");
        break;
}
