function notifyAboutCookies (message, close) {
    const cname = 'bbcookie';
    function set() {
        const d = new Date();
        d.setTime(d.getTime() + (365*24*60*60*1000));
        document.cookie = cname + '=true;expires=' + d.toUTCString() + ';path=/';
    }
    function get() {
        let name = cname + '=';
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) === ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "false";
      }
      if (get() === 'true') {
          set();
          return;
      }
      const mod = document.createElement('div');
      document.body.appendChild(mod);
      mod.setAttribute('id', 'cookie-banner');
      mod.appendChild(document.createElement('h2'));
      mod.lastChild.appendChild(document.createTextNode('Cookies'));
      mod.appendChild(document.createElement('p'));
      mod.lastChild.appendChild(document.createTextNode(message));
      const button = document.createElement('button');
      button.appendChild(document.createTextNode(close));
      mod.appendChild(button);
      button.setAttribute('title', 'Accept & Close Banner');
      button.onclick = () => {
          set();
          _paq.push(['rememberCookieConsentGiven']);
          document.body.removeChild(mod);
      };
};