function wpurp_bigoven() {var BO_HOST = 'http://www.bigoven.com'; var x = document.createElement('script'); var parentUrl = document.URL; x.type = 'text/javascript'; x.src = BO_HOST + '/assets/noexpire/js/getrecipe.js?' + (new Date().getTime() / 100000); document.getElementsByTagName('head')[0].appendChild(x); }