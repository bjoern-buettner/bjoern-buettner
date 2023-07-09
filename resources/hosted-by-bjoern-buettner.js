window.setTimeout(() => {
    const language = navigator.language || 'en';
    const head = document.head || document.getElementsByTagName('head')[0];
    const body = document.body || document.getElementsByTagName('body')[0];
    const el = document.createElement('a');
    el.setAttribute('href', 'https://bjoern-buettner.me/' + (language === 'de' ? 'de' : 'en'));
    el.setAttribute('target', '_blank');
    el.setAttribute('rel', 'external');
    el.setAttribute('id', 'bjoern-buettner-advertisement');
    el.setAttribute('title', language === 'de' ? 'Besuchen Sie die Webseite von Björn Büttner' : 'Visit the homepage of Björn Büttner');
    el.setAttribute('hreflang', language === 'de' ? 'de' : 'en');
    el.appendChild(document.createElement('span'));
    el.lastChild.appendChild(document.createTextNode(language === 'de' ? 'Gehostet von' : 'Hosted by'));
    const img = document.createElement('img')
    img.setAttribute('src', 'https://bjoern-buettner.me/logo.png');
    img.setAttribute('alt', language === 'de' ? 'Logo von Björn Büttner' : 'Logo of Björn Büttner');
    el.appendChild(img);
    body.appendChild(el);
    const style = document.createElement('link');
    style.setAttribute('rel', 'stylesheet');
    style.setAttribute('href', 'https://bjoern-buettner.me/hosted-by-bjoern-buettner.css');
    head.insertBefore(style, head.firstChild)
}, 1000);