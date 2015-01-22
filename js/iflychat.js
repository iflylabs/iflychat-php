LazyLoad=function(k){function p(b,a){var g=k.createElement(b),c;for(c in a)a.hasOwnProperty(c)&&g.setAttribute(c,a[c]);return g}function l(b){var a=m[b],c,f;if(a)c=a.callback,f=a.urls,f.shift(),h=0,f.length||(c&&c.call(a.context,a.obj),m[b]=null,n[b].length&&j(b))}function w(){var b=navigator.userAgent;c={async:k.createElement("script").async===!0};(c.webkit=/AppleWebKit\//.test(b))||(c.ie=/MSIE/.test(b))||(c.opera=/Opera/.test(b))||(c.gecko=/Gecko\//.test(b))||(c.unknown=!0)}function j(b,a,g,f,h){var j=
        function(){l(b)},o=b==="css",q=[],d,i,e,r;c||w();if(a)if(a=typeof a==="string"?[a]:a.concat(),o||c.async||c.gecko||c.opera)n[b].push({urls:a,callback:g,obj:f,context:h});else{d=0;for(i=a.length;d<i;++d)n[b].push({urls:[a[d]],callback:d===i-1?g:null,obj:f,context:h})}if(!m[b]&&(r=m[b]=n[b].shift())){s||(s=k.head||k.getElementsByTagName("head")[0]);a=r.urls;d=0;for(i=a.length;d<i;++d)g=a[d],o?e=c.gecko?p("style"):p("link",{href:g,rel:"stylesheet"}):(e=p("script",{src:g}),e.async=!1),e.className="lazyload",
        e.setAttribute("charset","utf-8"),c.ie&&!o?e.onreadystatechange=function(){if(/loaded|complete/.test(e.readyState))e.onreadystatechange=null,j()}:o&&(c.gecko||c.webkit)?c.webkit?(r.urls[d]=e.href,t()):(e.innerHTML='@import "'+g+'";',u(e)):e.onload=e.onerror=j,q.push(e);d=0;for(i=q.length;d<i;++d)s.appendChild(q[d])}}function u(b){var a;try{a=!!b.sheet.cssRules}catch(c){h+=1;h<200?setTimeout(function(){u(b)},50):a&&l("css");return}l("css")}function t(){var b=m.css,a;if(b){for(a=v.length;--a>=0;)if(v[a].href===
        b.urls[0]){l("css");break}h+=1;b&&(h<200?setTimeout(t,50):l("css"))}}var c,s,m={},h=0,n={css:[],js:[]},v=k.styleSheets;return{css:function(b,a,c,f){j("css",b,a,c,f)},js:function(b,a,c,f){j("js",b,a,c,f)}}}(this.document);
    function drupalchatCreateCookie(name,value,minutes) {
        if (minutes) {
            var date = new Date();
            date.setTime(date.getTime()+(minutes*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie =

            +"="+value+expires+"; path=/";
    }

    function drupalchatReadCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function drupalchatEraseCookie(name) {
        drupalchatCreateCookie(name,"",-1);
    }

    function drupalchatCheckUrl(url, sdata){
        (function($){
            if ($.browser.msie && window.XDomainRequest) {
                var xdr = new XDomainRequest();
                xdr.open("get", url);
                xdr.onload = function () {
                    $('body').append('<style>'+xdr.responseText+'</style>');
                    drupalchatLoad(sdata);
                };
                xdr.onerror = function (e) {
                    drupalchatForceLoad();
                };
                xdr.send();
            }
            else {
                var request = new XMLHttpRequest;
                request.open('GET', url, true);
                request.send();
                request.onreadystatechange = function(){
                    if(request.readyState==4 && request.status==200){
                        $('body').append('<style>'+request.responseText+'</style>');
                        drupalchatLoad(sdata);
                    }
                    else if (request.readyState==4 && request.status!=200){
                        drupalchatForceLoad();
                    }
                };
            }
        })(jQuery);
    }

    function drupalchatForceLoad() {
        (function($){
            $.post(Drupal.settings.drupalchat.exurl ,function(data) {
                if(typeof(data) === "string") {
                    if(typeof(JSON) !== "undefined") {
                        data = JSON.parse(data);
                    }
                }
                drupalchatLoad(data);
                if(Drupal.settings.drupalchat.chat_type === '2') {
                    drupalchatCreateCookie('iflychat_key', data.key, 30);
                    drupalchatCreateCookie('iflychat_css', data.css, 30);
                    drupalchatCreateCookie('iflychat_time', new Date().getTime(), 30);
                }
            });
        })(jQuery);
    }

    function drupalchatLoad(data) {
        if(data && (typeof data.css != "undefined") && (typeof data.key != "undefined")) {
            Drupal.settings.drupalchat.session_key = data.key;
            if(typeof data.uname !== "undefined") {
                Drupal.settings.drupalchat.username = data.uname;

            }
            if(typeof data.uid !== "undefined") {
                Drupal.settings.drupalchat.uid = data.uid;
            }
            if(typeof data.up !== "undefined") {
                Drupal.settings.drupalchat.up = data.up;
            }
            if(typeof data.upl !== "undefined") {
                Drupal.settings.drupalchat.upl = data.upl;

            }
            if(Drupal.settings.drupalchat.chat_type === '2') {
                if(typeof data.cache === "undefined") {
                    /*LazyLoad.css(Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/i/' + data.css + '/cache.css', function () {
                     LazyLoad.js([Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/j/cache.js', Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/h/'+ data.css + '/cache.js'], function () {
                     });
                     });*/
                    LazyLoad.css(['//cdn.iflychat.com/css/iflychat-'+Drupal.settings.drupalchat.theme+'.css', Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/i/' + data.css + '/settings/no.cache.css'], function () {
                        LazyLoad.js([Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/h/'+ data.css + '/settings/no.cache.js', '//cdn.iflychat.com/js/iflychat.min.js'], function () {
                        });
                    });
                }
//                else {
//                    /*LazyLoad.js([Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/j/cache.js', Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/h/'+ data.css + '/cache.js'], function () {
//                     });*/
//                }

            }
            else {
                LazyLoad.css(Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/i/' + data.css + '/s/'+((Drupal.settings.drupalchat.admin=="1")?('a/'):(''))+'cache.css', function () {
                    LazyLoad.js([Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/j/cache.js', Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/h/'+ data.css + '/s' +((Drupal.settings.drupalchat.admin=="1")?('/a'):('')) +'/cache.js'], function () {

                    });
                });
            }

        }
    }

$(document).ready(function(){

        if(false && drupalchatReadCookie('iflychat_key') && drupalchatReadCookie('iflychat_css') && (Drupal.settings.drupalchat.chat_type === '2')) {
            var data = {key: drupalchatReadCookie('iflychat_key'), css: drupalchatReadCookie('iflychat_css'), cache: '1'};
            drupalchatCheckUrl(Drupal.settings.drupalchat.external_a_host + ':' + Drupal.settings.drupalchat.external_a_port + '/i/' + drupalchatReadCookie('iflychat_css') + '/cache.css', data);
        }
        else {
            drupalchatForceLoad();
        }

});


