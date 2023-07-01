window.setTimeout(() => {
    const body = document.body || document.getElementsByTagName('body')[0];
    const el = document.createElement('a');
    el.setAttribute('href', 'https://bjoern-buettner.me');
    el.setAttribute('target', '_blank');
    el.setAttribute('id', 'bjoern-buettner-advertisement');
    el.setAttribute('style', 'position: fixed;bottom: 0;right: 0;width: auto; height: auto;');
    el.onclick = () => {
        body.removeChild(el);
    };
    el.appendChild(document.createElement('span'));
    el.lastChild.appendChild(document.createTextNode('Hosted by'));
    el.lastChild.setAttribute('style', 'line-height: initial;display: block;background-color: darkgreen;background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.2), rgba(0, 0, 0, 0)), linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.25));color: #fff;text-align: center;font-size: 0.5em;');
    el.appendChild(document.createElement('img'));
    el.lastChild.setAttribute('src', 'https://bjoern-buettner.me/logo.png');
    el.lastChild.setAttribute('style', 'display: block;width: 53px;height: 32px;');
    body.appendChild(el);
}, 1000);