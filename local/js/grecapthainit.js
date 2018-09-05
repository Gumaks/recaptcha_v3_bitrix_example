(function (window) {
    'use strict';

    window.GrecapthaOir = function (params) {

        this.siteKey = params;
        this.token = '';

        BX.ready(BX.delegate(this.init, this));
    };

    window.GrecapthaOir.prototype = {

        init: function () {
            this.reloadToken();
        },

        getToken: function () {
            //пользуемся этим методом когда нужно получить токен
            let oldToken = this.token;
            this.reloadToken();
            return oldToken;
        },
            //получаем токен и храним его в объекте
        reloadToken: function () {
            if (typeof(grecaptcha) !== "undefined") {
                let sitekey = this.siteKey;
                let obj = this;
                grecaptcha.ready(function () {
                    grecaptcha.execute(sitekey, {action: 'homepage'}).then(function (token) {
                        obj.token = token;
                    });
                });
            }
        }
    };

})(window);
BX.ready(function () {
    //получаем ключ сайта
    let siteKey = BX.message('grecsitekey');
    //объект сохраняем в window
    window.RecObj = new window.GrecapthaOir(siteKey);
});