window.setTimeout(() => {
    const head = document.head || document.getElementsByTagName('head')[0];
    const body = document.body || document.getElementsByTagName('body')[0];
    const el = document.createElement('a');
    el.setAttribute('href', 'https://bjoern-buettner.me');
    el.setAttribute('target', '_blank');
    el.setAttribute('id', 'bjoern-buettner-advertisement');
    el.onclick = () => {
        body.removeChild(el);
    };
    el.appendChild(document.createElement('span'));
    el.lastChild.appendChild(document.createTextNode('Hosted by'));
    el.appendChild(document.createElement('img'));
    el.lastChild.setAttribute('src', 'https://bjoern-buettner.me/logo.png');
    el.lastChild.setAttribute('alt', 'Logo of Bjoern Buettner');
    body.appendChild(el);
    const style = document.createElement('link');
    style.setAttribute('rel', 'stylesheet');
    style.setAttribute('href', 'https://bjoern-buettner.me/hosted-by-bjoern-buettner.css');
    head.insertBefore(style, head.firstChild)
}, 1000);