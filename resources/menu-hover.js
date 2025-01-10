(() => {
    const els = document.getElementById('header').firstElementChild.getElementsByTagName('ul');
    for (let i = 0; i < els.length; i++) {
        const li = els[i].parentElement;
        li.addEventListener('mouseenter', (e) => {
            li.lastElementChild.style.left = li.offsetLeft + 'px';
        })
    }
})();