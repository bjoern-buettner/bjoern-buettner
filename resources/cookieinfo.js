function notifyAboutCookies (message) {
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
      mod.setAttribute('style', 'position: absolute;top: 10%;right: 0;width: 200px;background: #eee;border:#fff');
      mod.appendChild(document.createElement('p'));
      mod.lastChild.appendChild(document.createTextNode(message));
      button = document.createElement('button');
      mod.appendChild(button);
      button.onclick = () => {
          set();
          document.body.removeChild(mod.lastChild);
      };
};