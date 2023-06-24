(() => {
    const el = document.createElement('a');
    el.setAttribute('href', 'https://bjoern-buettner.me');
    el.setAttribute('target', '_blank');
    el.setAttribute('id', 'bjoern-buettner-advertisement');
    el.setAttribute('style', 'position: fixed;bottom: 0;right: 0;width: 25%; height: 25%;max-width: 106px;max-height: 64px;min-width: 53px;min-height: 32px');
    el.appendChild(document.createElement('span'));
    el.lastChild.appendChild(document.createTextNode('Hosted by Björn Büttner'));
    el.lastChild.setAttribute('style', 'display: block;background: rgba(0,0,0,0.5);color: #fff;text-align: center;font-size: 0.5em;');
    el.appendChild(document.createElement('img'));
    el.lastChild.setAttribute('src', 'https://bjoern-buettner.me/logo.png');
    el.setAttribute('style', 'display: block;max-width: 106px;max-height: 64px;min-width: 53px;min-height: 32px;');
    document.body.appendChild(el);
})();