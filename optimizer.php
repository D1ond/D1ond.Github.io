/*
 MIT
 @namespace TraceKit
*/
(function(l,B){function J(u){return"undefined"===typeof u}if(l){var m={},P=l.TraceKit,G=[].slice,K=/^(?:[Uu]ncaught (?:exception: )?)?(?:((?:Eval|Internal|Range|Reference|Syntax|Type|URI|)Error): )?(.*)$/;m.noConflict=function(){l.TraceKit=P;return m};m.wrap=function(u){return function(){try{return u.apply(this,arguments)}catch(v){throw m.report(v),v;}}};m.report=function(){function u(c,a,b){var d=null;if(!a||m.collectWindowErrors){for(var e in r)if(Object.prototype.hasOwnProperty.call(r,e))try{r[e](c,
a,b)}catch(f){d=f}if(d)throw d;}}function v(c,a,b,d,e){if(C)m.computeStackTrace.augmentStackTraceWithInitialElement(C,a,b,c),z();else if(e){var f=m.computeStackTrace(e);u(f,!0,e)}else{f={url:a,line:b,column:d};var n=c;if("[object String]"==={}.toString.call(c)){var p=c.match(K);if(p){var h=p[1];n=p[2]}}f.func=m.computeStackTrace.guessFunctionName(f.url,f.line);f.context=m.computeStackTrace.gatherContext(f.url,f.line);f={name:h,message:n,mode:"onerror",stack:[f]};u(f,!0,null)}return H?H.apply(this,
arguments):!1}function A(c){var a=m.computeStackTrace(c.reason);u(a,!0,c.reason)}function z(){var c=C,a=D;D=C=null;u(c,!1,a)}function E(c){if(C){if(D===c)return;z()}var a=m.computeStackTrace(c);C=a;D=c;setTimeout(function(){D===c&&z()},a.incomplete?2E3:0);throw c;}var r=[],D=null,C=null,H,F,I,y;E.subscribe=function(c){!0!==F&&(H=l.onerror,l.onerror=v,F=!0);!0!==y&&(I=l.onunhandledrejection,l.onunhandledrejection=A,y=!0);r.push(c)};E.unsubscribe=function(c){for(var a=r.length-1;0<=a;--a)r[a]===c&&
r.splice(a,1);0===r.length&&(F&&(l.onerror=H,F=!1),y&&(l.onunhandledrejection=I,y=!1))};return E}();m.computeStackTrace=function(){function u(a){if("string"!==typeof a)return[];if(!Object.prototype.hasOwnProperty.call(c,a)){var b="",d="";try{d=l.document.domain}catch(n){}var e=/(.*):\/\/([^:\/]+)([:\d]*)\/{0,1}([\s\S]*)/.exec(a);if(e&&e[2]===d)if(m.remoteFetching)try{try{var f=new l.XMLHttpRequest}catch(n){f=new l.ActiveXObject("Microsoft.XMLHTTP")}f.open("GET",a,!1);f.send("");b=f.responseText}catch(n){b=
""}else b="";c[a]=b?b.split("\n"):[]}return c[a]}function v(a,b){var d=/function ([^(]*)\(([^)]*)\)/,e=/['"]?([0-9A-Za-z$_]+)['"]?\s*[:=]\s*(function|eval|new Function)/,f="";a=u(a);var n;if(!a.length)return"?";for(var p=0;10>p;++p)if(f=a[b-p]+f,!J(f)&&((n=e.exec(f))||(n=d.exec(f))))return n[1];return"?"}function A(a,b){a=u(a);if(!a.length)return null;var d=[],e=Math.floor(m.linesOfContext/2),f=Math.min(a.length,b+(e+m.linesOfContext%2)-1);for(b=Math.max(0,b-e-1);b<f;++b)J(a[b])||d.push(a[b]);return 0<
d.length?d:null}function z(a){return a.replace(/[\-\[\]{}()*+?.,\\\^$|#]/g,"\\$&")}function E(a){return z(a).replace("<","(?:<|&lt;)").replace(">","(?:>|&gt;)").replace("&","(?:&|&amp;)").replace('"','(?:"|&quot;)').replace(/\s+/g,"\\s+")}function r(a,b){for(var d,e,f=0,n=b.length;f<n;++f)if((d=u(b[f])).length&&(d=d.join("\n"),e=a.exec(d)))return{url:b[f],line:d.substring(0,e.index).split("\n").length,column:e.index-d.lastIndexOf("\n",e.index)-1};return null}function D(a,b,d){b=u(b);a=new RegExp("\\b"+
z(a)+"\\b");var e;--d;return b&&b.length>d&&(e=a.exec(b[d]))?e.index:null}function C(a){if(!J(l&&l.document)){var b=[l.location.href],d=l.document.getElementsByTagName("script");a=""+a;var e;for(e=0;e<d.length;++e){var f=d[e];f.src&&b.push(f.src)}(d=/^function(?:\s+([\w$]+))?\s*\(([\w\s,]*)\)\s*\{\s*(\S[\s\S]*\S)\s*\}\s*$/.exec(a))?(e=d[1]?"\\s+"+d[1]:"",f=d[2].split(",").join("\\s*,\\s*"),d=z(d[3]).replace(/;$/,";?"),e=new RegExp("function"+e+"\\s*\\(\\s*"+f+"\\s*\\)\\s*{\\s*"+d+"\\s*}")):e=new RegExp(z(a).replace(/\s+/g,
"\\s+"));if(e=r(e,b))return e;if(d=/^function on([\w$]+)\s*\(event\)\s*\{\s*(\S[\s\S]*\S)\s*\}\s*$/.exec(a)){a=d[1];d=E(d[2]);e=new RegExp("on"+a+"=[\\'\"]\\s*"+d+"\\s*[\\'\"]","i");if(e=r(e,b[0]))return e;e=new RegExp(d);if(e=r(e,b))return e}return null}}function H(a){if(!a.stack)return null;for(var b=/^\s*at (.*?) ?\(((?:file|https?|blob|chrome-extension|native|eval|webpack|<anonymous>|\/).*?)(?::(\d+))?(?::(\d+))?\)?\s*$/i,d=/^\s*(.*?)(?:\((.*?)\))?(?:^|@)((?:file|https?|blob|chrome|webpack|resource|\[native).*?|[^@]*bundle)(?::(\d+))?(?::(\d+))?\s*$/i,
e=/^\s*at (?:((?:\[object object\])?.+) )?\(?((?:file|ms-appx|https?|webpack|blob):.*?):(\d+)(?::(\d+))?\)?\s*$/i,f,n=/(\S+) line (\d+)(?: > eval line \d+)* > eval/i,p=/\((\S*)(?::(\d+))(?::(\d+))\)/,h=a.stack.split("\n"),q=[],k,g,x=/^(.*) is undefined$/.exec(a.message),w=0,t=h.length;w<t;++w){if(g=b.exec(h[w])){var M=g[2]&&0===g[2].indexOf("native");(f=g[2]&&0===g[2].indexOf("eval"))&&(k=p.exec(g[2]))&&(g[2]=k[1],g[3]=k[2],g[4]=k[3]);f={url:M?null:g[2],func:g[1]||"?",args:M?[g[2]]:[],line:g[3]?+g[3]:
null,column:g[4]?+g[4]:null}}else if(g=e.exec(h[w]))f={url:g[2],func:g[1]||"?",args:[],line:+g[3],column:g[4]?+g[4]:null};else if(g=d.exec(h[w]))(f=g[3]&&-1<g[3].indexOf(" > eval"))&&(k=n.exec(g[3]))?(g[3]=k[1],g[4]=k[2],g[5]=null):0!==w||g[5]||J(a.columnNumber)||(q[0].column=a.columnNumber+1),f={url:g[3],func:g[1]||"?",args:g[2]?g[2].split(","):[],line:g[4]?+g[4]:null,column:g[5]?+g[5]:null};else continue;!f.func&&f.line&&(f.func=v(f.url,f.line));f.context=f.line?A(f.url,f.line):null;q.push(f)}if(!q.length)return null;
q[0]&&q[0].line&&!q[0].column&&x&&(q[0].column=D(x[1],q[0].url,q[0].line));return{mode:"stack",name:a.name,message:a.message,stack:q}}function F(a,b,d,e){b={url:b,line:d};if(b.url&&b.line){a.incomplete=!1;b.func||(b.func=v(b.url,b.line));b.context||(b.context=A(b.url,b.line));if(e=/ '([^']+)' /.exec(e))b.column=D(e[1],b.url,b.line);if(0<a.stack.length&&a.stack[0].url===b.url){if(a.stack[0].line===b.line)return!1;if(!a.stack[0].line&&a.stack[0].func===b.func)return a.stack[0].line=b.line,a.stack[0].context=
b.context,!1}a.stack.unshift(b);return a.partial=!0}a.incomplete=!0;return!1}function I(a,b){for(var d=/function\s+([_$a-zA-Z\xA0-\uFFFF][_$a-zA-Z0-9\xA0-\uFFFF]*)?\s*\(/i,e=[],f={},n=!1,p,h,q,k=I.caller;k&&!n;k=k.caller)if(k!==y&&k!==m.report){h={url:null,func:"?",args:[],line:null,column:null};if(k.name)h.func=k.name;else if(p=d.exec(k.toString()))h.func=p[1];if("undefined"===typeof h.func)try{h.func=p.input.substring(0,p.input.indexOf("{"))}catch(x){}if(q=C(k)){h.url=q.url;h.line=q.line;"?"===
h.func&&(h.func=v(h.url,h.line));var g=/ '([^']+)' /.exec(a.message||a.description);g&&(h.column=D(g[1],q.url,q.line))}f[""+k]?n=!0:f[""+k]=!0;e.push(h)}b&&e.splice(0,b);b={mode:"callers",name:a.name,message:a.message,stack:e};F(b,a.sourceURL||a.fileName,a.line||a.lineNumber,a.message||a.description);return b}function y(a,b){var d=null;b=null==b?0:+b;try{var e=a.stacktrace;if(e){var f=/ line (\d+).*script (?:in )?(\S+)(?:: in function (\S+))?$/i,n=/ line (\d+), column (\d+)\s*(?:in (?:<anonymous function: ([^>]+)>|([^\)]+))\((.*)\))? in (.*):\s*$/i,
p=e.split("\n");e=[];for(var h,q=0;q<p.length;q+=2){var k=null;if(h=f.exec(p[q]))k={url:h[2],line:+h[1],column:null,func:h[3],args:[]};else if(h=n.exec(p[q]))k={url:h[6],line:+h[1],column:+h[2],func:h[3]||h[4],args:h[5]?h[5].split(","):[]};if(k){!k.func&&k.line&&(k.func=v(k.url,k.line));if(k.line)try{k.context=A(k.url,k.line)}catch(Q){}k.context||(k.context=[p[q+1]]);e.push(k)}}d=e.length?{mode:"stacktrace",name:a.name,message:a.message,stack:e}:null}else d=void 0;if(d)return d}catch(Q){}try{if(d=
H(a))return d}catch(Q){}try{var g=a.message.split("\n");if(4>g.length)d=null;else{f=/^\s*Line (\d+) of linked script ((?:file|https?|blob)\S+)(?:: in function (\S+))?\s*$/i;n=/^\s*Line (\d+) of inline#(\d+) script in ((?:file|https?|blob)\S+)(?:: in function (\S+))?\s*$/i;p=/^\s*Line (\d+) of function script\s*$/i;h=[];var x=l&&l.document&&l.document.getElementsByTagName("script");e=[];var w;for(t in x)Object.prototype.hasOwnProperty.call(x,t)&&!x[t].src&&e.push(x[t]);for(x=2;x<g.length;x+=2){var t=
null;if(w=f.exec(g[x]))t={url:w[2],func:w[3],args:[],line:+w[1],column:null};else if(w=n.exec(g[x])){t={url:w[3],func:w[4],args:[],line:+w[1],column:null};var M=+w[1],N=e[w[2]-1];if(N){var L=u(t.url);if(L){L=L.join("\n");var R=L.indexOf(N.innerText);0<=R&&(t.line=M+L.substring(0,R).split("\n").length)}}}else if(w=p.exec(g[x])){var S=l.location.href.replace(/#.*$/,""),U=new RegExp(E(g[x+1])),T=r(U,[S]);t={url:S,func:"",args:[],line:T?T.line:w[1],column:null}}if(t){t.func||(t.func=v(t.url,t.line));
var O=A(t.url,t.line),V=O?O[Math.floor(O.length/2)]:null;O&&V.replace(/^\s*/,"")===g[x+1].replace(/^\s*/,"")?t.context=O:t.context=[g[x+1]];h.push(t)}}d=h.length?{mode:"multiline",name:a.name,message:g[0],stack:h}:null}if(d)return d}catch(Q){}try{if(d=I(a,b+1))return d}catch(Q){}return{name:a.name,message:a.message,mode:"failed"}}var c={};y.augmentStackTraceWithInitialElement=F;y.computeStackTraceFromStackProp=H;y.guessFunctionName=v;y.gatherContext=A;y.ofCaller=function(a){try{throw Error();}catch(b){return y(b,
(null==a?0:+a)+2)}};y.getSource=u;return y}();m.extendToAsynchronousCallbacks=function(){var u=function(v){var A=l[v];l[v]=function(){var z=G.call(arguments),E=z[0];"function"===typeof E&&(z[0]=m.wrap(E));return A.apply?A.apply(this,z):A(z[0],z[1])}};u("setTimeout");u("setInterval")};m.remoteFetching||(m.remoteFetching=!0);m.collectWindowErrors||(m.collectWindowErrors=!0);if(!m.linesOfContext||1>m.linesOfContext)m.linesOfContext=11;"function"===typeof define&&define.amd?define("TraceKit",[],m):"undefined"!==
typeof module&&module.exports&&l.module!==module?module.exports=m:l.TraceKit=m}})("undefined"!==typeof window?window:global);var EC_JET=function(){var l=TraceKit.noConflict();l.remoteFetching=!1;var B={path:"/api/v1/store",token:null,collectWindowErrors:!0,preventDuplicateReport:!0,storageKeyPrefix:"EC_JET.MAIN",timeout:500,ignoreErrors:[],ignoreUrls:[]},J,m,P=!1,G=!1,K=!1,u=[],v={},A={data:[],size:function(){return this.data.length},enqueue:function(c){this.data.push(c)},dequeue:function(){return this.data.shift()}},z=function(){Date.now||(Date.now=function(){return(new Date).getTime()});Date.prototype.toISOString||function(){function c(a){return 10>
a?"0"+a:a}Date.prototype.toISOString=function(){return this.getUTCFullYear()+"-"+c(this.getUTCMonth()+1)+"-"+c(this.getUTCDate())+"T"+c(this.getUTCHours())+":"+c(this.getUTCMinutes())+":"+c(this.getUTCSeconds())+"."+(this.getUTCMilliseconds()/1E3).toFixed(3).slice(2,5)+"Z"}}();window.location.origin||(window.location.origin=window.location.protocol+"//"+window.location.hostname+(window.location.port?":"+window.location.port:""))},E=function(c){var a={token:r("token"),source_origin:encodeURIComponent(window.location.origin)};
return J=c+r("path")+"?"+Object.keys(a).map(function(b){return"".concat(b,"=").concat(a[b])}).join("&")},r=function(c){return!0===B.hasOwnProperty(c)?B[c]:null},D=function(c){for(var a=[],b=c.length,d,e=0;e<b;e++)d=c[e],"string"===typeof d?a.push(d.replace(/([.*+?^=!:${}()|\[\]\/\\])/g,"\\$1")):d&&d.source&&a.push(d.source);return new RegExp(a.join("|"),"i")},C=function(c,a){try{if(5<=u.length)G=!1,K=!0,l.report.unsubscribe(C);else if(!0!==K){a:{if(c){if(c.hasOwnProperty("message")&&r("ignoreErrors").test&&
r("ignoreErrors").test(c.message)){var b=!0;break a}if("stack"in c&&0<c.stack.length){var d=c.stack[0];if(d.hasOwnProperty("url")&&r("ignoreUrls").test&&r("ignoreUrls").test(d.url)){b=!0;break a}}}b=!1}var e;if(e=!0!==b){var f;(f=!0!==a)||(f=!0!==(0===Object.keys(v).length||!1===v.hasOwnProperty("error")||!1===v.hasOwnProperty("time")?!1:500>Date.now()-v.time&&v.error.message===c.message?!0:!1));e=f}if(e){var n;if(n=!0===a&&!0===r("preventDuplicateReport")){var p=JSON.stringify(c),h=r("storageKeyPrefix")+
".";b=5381;for(var q=p.length;q;)b=33*b^p.charCodeAt(--q);var k=h+(b>>>0);if(null!=sessionStorage.getItem(k)&&sessionStorage.getItem(k).trim()===p.trim())var g=!0;else sessionStorage.setItem(k,p),g=!1;n=!0===g}if(!n){v={error:c,time:Date.now()};var x=decodeURIComponent(window.location.href);var w=window.navigator.languages?window.navigator.languages[0]:window.navigator.userLanguage||window.navigator.language;var t={locale:w,datetime:(new Date).toISOString(),user_agent:window.navigator.userAgent,screen_width:window.screen.width,
screen_height:window.screen.height},M=Date.now()-m;n={};window.$&&window.$.fn&&(n.jquery_version=$.fn.jquery);var N={url:x,report:c,is_window_error:a,device:t,runtime:M,extra_data:n,referer:document.referrer,is_login:!!document.cookie.match(/(?:^| |;)iscache=F/)};"complete"===document.readyState?I(N):A.enqueue(N)}}}}catch(L){throw L;}},H=function(){if("complete"===document.readyState&&!0!==K)for(var c=A.size(),a=0;a<c;a++)setTimeout(function(){I(A.dequeue())},100*a)},F=function(c,a,b){u.push({type:c,
url:a,payload:b})},I=function(c){if(!0!==K){var a=JSON.stringify(c),b=J,d=new XMLHttpRequest;"withCredentials"in d?(d.open("POST",b,!0),d.timeout=r("timeout",500),d.onreadystatechange=function(){if(d.readyState===XMLHttpRequest.DONE){var e=d.status;-1!==[429,500].indexOf(e)?(F("xhr",b,a),G=!1,K=!0,l.report.unsubscribe(C)):200!==e&&F("xhr",b,a)}},d.send(a)):(c=new Image,c.src=b+"&payload="+encodeURIComponent(a),c.onerror=function(){F("beacon",b,a)})}},y=function(c){if("object"!==typeof c)return!1;
switch({}.toString.call(c)){case "[object Error]":return!0;case "[object Exception]":return!0;case "[object DOMException]":return!0;default:return c instanceof Error}};return{init:function(c,a){if("string"!==typeof c||"object"!==typeof a)throw new TypeError("Invalid Type Error.");if(!0!==P){z();m=Date.now();a=a||{};for(key in B)!0===a.hasOwnProperty(key)&&(B[key]=a[key]);B.ignoreErrors.length&&(B.ignoreErrors=D(B.ignoreErrors));B.ignoreUrls.push(/^chrome-extension/,/^moz-extension/);B.ignoreUrls.length&&
(B.ignoreUrls=D(B.ignoreUrls));l.collectWindowErrors=!!B.collectWindowErrors;E(c);if(!0!==G)try{document.onreadystatechange=H,l.report.subscribe(C),G=!0}catch(b){throw b;}P=!0}},wrap:function(c){if(!1!==G&&"function"===typeof c)return l.wrap(c)},report:function(c){!1!==G&&!1!==y(c)&&l.report(c)},message:function(c){if(!1!==G&&(!1!==y(c)||"string"===typeof c)){if("string"===typeof c){var a=l.computeStackTrace.ofCaller();a.message=c}else a=l.computeStackTrace(c);C(a,!1)}}}}();

