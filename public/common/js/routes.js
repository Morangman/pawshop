(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"home","action":"App\Http\Controllers\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"comments","name":"comments","action":"App\Http\Controllers\HomeController@comments"},{"host":null,"methods":["GET","HEAD"],"uri":"cart","name":"cart","action":"App\Http\Controllers\HomeController@cart"},{"host":null,"methods":["GET","HEAD"],"uri":"support","name":"support","action":"App\Http\Controllers\HomeController@support"},{"host":null,"methods":["GET","HEAD"],"uri":"terms","name":"terms","action":"App\Http\Controllers\HomeController@terms"},{"host":null,"methods":["GET","HEAD"],"uri":"user_agreement","name":"user_agreement","action":"App\Http\Controllers\HomeController@userAgreement"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy_policy","name":"privacy_policy","action":"App\Http\Controllers\HomeController@privacyPolicy"},{"host":null,"methods":["GET","HEAD"],"uri":"law_enforcement","name":"law_enforcement","action":"App\Http\Controllers\HomeController@lawEnforcement"},{"host":null,"methods":["GET","HEAD"],"uri":"checkout","name":"checkout","action":"App\Http\Controllers\HomeController@checkout"},{"host":null,"methods":["GET","HEAD"],"uri":"account","name":"account","action":"App\Http\Controllers\HomeController@account"},{"host":null,"methods":["GET","HEAD"],"uri":"header-search","name":"header-search","action":"App\Http\Controllers\HomeController@headerSearchDevice"},{"host":null,"methods":["GET","HEAD"],"uri":"get-category\/{slug}","name":"get-category","action":"App\Http\Controllers\HomeController@getByCategory"},{"host":null,"methods":["GET","HEAD"],"uri":"order\/{order_uuid}\/thanks","name":"thanks","action":"App\Http\Controllers\HomeController@thanks"},{"host":null,"methods":["GET","HEAD"],"uri":"order\/{order_uuid}\/confirm-order","name":"confirm-order","action":"App\Http\Controllers\HomeController@confirmOrder"},{"host":null,"methods":["GET","HEAD"],"uri":"{order}\/fedex-label","name":"fedex-label","action":"App\Http\Controllers\HomeController@getFedexLabel"},{"host":null,"methods":["GET","HEAD"],"uri":"redirect-google","name":"redirect-google","action":"App\Http\Controllers\Auth\LoginController@redirectToGoogleProvider"},{"host":null,"methods":["GET","HEAD"],"uri":"redirect-facebook","name":"redirect-facebook","action":"App\Http\Controllers\Auth\LoginController@redirectToFacebookProvider"},{"host":null,"methods":["GET","HEAD"],"uri":"callback-google","name":"callback-google","action":"App\Http\Controllers\Auth\LoginController@handleProviderGoogleCallback"},{"host":null,"methods":["GET","HEAD"],"uri":"callback-facebook","name":"callback-facebook","action":"App\Http\Controllers\Auth\LoginController@handleProviderFacebookCallback"},{"host":null,"methods":["POST"],"uri":"callback","name":"callback","action":"App\Http\Controllers\HomeController@callback"},{"host":null,"methods":["POST"],"uri":"order","name":"order","action":"App\Http\Controllers\HomeController@makeOrder"},{"host":null,"methods":["POST"],"uri":"get-price","name":"get-price","action":"App\Http\Controllers\HomeController@getPrice"},{"host":null,"methods":["POST"],"uri":"add-to-box\/{slug}","name":"add-to-box","action":"App\Http\Controllers\HomeController@addToBox"},{"host":null,"methods":["POST"],"uri":"comment","name":"comment","action":"App\Http\Controllers\HomeController@addComment"},{"host":null,"methods":["PATCH"],"uri":"update-account\/{user}","name":"update-account","action":"App\Http\Controllers\HomeController@updateAccountInfo"},{"host":null,"methods":["PATCH"],"uri":"update-order\/{order}","name":"update-order","action":"App\Http\Controllers\HomeController@updateOrderAddress"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/login","name":"web.login.show","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/logout","name":"web.logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["POST"],"uri":"web\/login","name":"web.login.post","action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/password\/reset","name":"web.password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"web\/password\/email","name":"web.password.email","action":"App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/password\/reset-success","name":"web.reset.success","action":"App\Http\Controllers\Auth\ForgotPasswordController@showSuccessPage"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/password\/reset\/{token}","name":"web.password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"web\/password\/reset","name":"web.password.reset.send","action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/register","name":"web.register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["GET","HEAD"],"uri":"web\/email\/verify\/{code}","name":"web.email.verify","action":"App\Http\Controllers\Auth\RegisterController@verifyEmail"},{"host":null,"methods":["POST"],"uri":"web\/register","name":"web.register.store","action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/user","name":"admin.user.index","action":"App\Http\Controllers\Admin\UserController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/user\/all","name":"admin.user.all","action":"App\Http\Controllers\Admin\UserController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/user\/create","name":"admin.user.create","action":"App\Http\Controllers\Admin\UserController@create"},{"host":null,"methods":["POST"],"uri":"admin\/user","name":"admin.user.store","action":"App\Http\Controllers\Admin\UserController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/user\/{user}\/edit","name":"admin.user.edit","action":"App\Http\Controllers\Admin\UserController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/user\/{user}","name":"admin.user.update","action":"App\Http\Controllers\Admin\UserController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/user\/{user}","name":"admin.user.get","action":"App\Http\Controllers\Admin\UserController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/user\/{user}","name":"admin.user.delete","action":"App\Http\Controllers\Admin\UserController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/admin","name":"admin.admin.index","action":"App\Http\Controllers\Admin\AdminController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/admin\/all","name":"admin.admin.all","action":"App\Http\Controllers\Admin\AdminController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/admin\/create","name":"admin.admin.create","action":"App\Http\Controllers\Admin\AdminController@create"},{"host":null,"methods":["POST"],"uri":"admin\/admin","name":"admin.admin.store","action":"App\Http\Controllers\Admin\AdminController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/admin\/{user}\/edit","name":"admin.admin.edit","action":"App\Http\Controllers\Admin\AdminController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/admin\/{user}","name":"admin.admin.update","action":"App\Http\Controllers\Admin\AdminController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/admin\/{user}","name":"admin.admin.get","action":"App\Http\Controllers\Admin\AdminController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/admin\/{user}","name":"admin.admin.delete","action":"App\Http\Controllers\Admin\AdminController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/category","name":"admin.category.index","action":"App\Http\Controllers\Admin\CategoryController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/category\/all","name":"admin.category.all","action":"App\Http\Controllers\Admin\CategoryController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/category\/create","name":"admin.category.create","action":"App\Http\Controllers\Admin\CategoryController@create"},{"host":null,"methods":["POST"],"uri":"admin\/category","name":"admin.category.store","action":"App\Http\Controllers\Admin\CategoryController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/category\/{slug}\/edit","name":"admin.category.edit","action":"App\Http\Controllers\Admin\CategoryController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/category\/{slug}","name":"admin.category.update","action":"App\Http\Controllers\Admin\CategoryController@update"},{"host":null,"methods":["POST"],"uri":"admin\/category\/{slug}\/update-price","name":"admin.category.update-price","action":"App\Http\Controllers\Admin\CategoryController@updatePrice"},{"host":null,"methods":["POST"],"uri":"admin\/category\/{slug}\/update-premium","name":"admin.category.update-premium","action":"App\Http\Controllers\Admin\CategoryController@updatePremiumPrice"},{"host":null,"methods":["POST"],"uri":"admin\/category\/{slug}\/generate-prices","name":"admin.category.generate-prices","action":"App\Http\Controllers\Admin\CategoryController@generatePricesVariations"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/category\/{slug}","name":"admin.category.get","action":"App\Http\Controllers\Admin\CategoryController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/category\/{slug}","name":"admin.category.delete","action":"App\Http\Controllers\Admin\CategoryController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/statistics","name":"admin.statistics.index","action":"App\Http\Controllers\Admin\StatisticsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/statistics\/all","name":"admin.statistics.all","action":"App\Http\Controllers\Admin\StatisticsController@getAll"},{"host":null,"methods":["POST"],"uri":"admin\/statistics\/{slug}\/update","name":"admin.statistics.update","action":"App\Http\Controllers\Admin\StatisticsController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/faq","name":"admin.faq.index","action":"App\Http\Controllers\Admin\FaqController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/faq\/all","name":"admin.faq.all","action":"App\Http\Controllers\Admin\FaqController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/faq\/create","name":"admin.faq.create","action":"App\Http\Controllers\Admin\FaqController@create"},{"host":null,"methods":["POST"],"uri":"admin\/faq","name":"admin.faq.store","action":"App\Http\Controllers\Admin\FaqController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/faq\/{faq}\/edit","name":"admin.faq.edit","action":"App\Http\Controllers\Admin\FaqController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/faq\/{faq}","name":"admin.faq.update","action":"App\Http\Controllers\Admin\FaqController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/faq\/{faq}","name":"admin.faq.get","action":"App\Http\Controllers\Admin\FaqController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/faq\/{faq}","name":"admin.faq.delete","action":"App\Http\Controllers\Admin\FaqController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/task","name":"admin.task.index","action":"App\Http\Controllers\Admin\TaskController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/task\/all","name":"admin.task.all","action":"App\Http\Controllers\Admin\TaskController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/task\/create","name":"admin.task.create","action":"App\Http\Controllers\Admin\TaskController@create"},{"host":null,"methods":["POST"],"uri":"admin\/task","name":"admin.task.store","action":"App\Http\Controllers\Admin\TaskController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/task\/{task}\/edit","name":"admin.task.edit","action":"App\Http\Controllers\Admin\TaskController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/task\/{task}","name":"admin.task.update","action":"App\Http\Controllers\Admin\TaskController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/task\/{task}","name":"admin.task.get","action":"App\Http\Controllers\Admin\TaskController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/task\/{task}","name":"admin.task.delete","action":"App\Http\Controllers\Admin\TaskController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/step","name":"admin.step.index","action":"App\Http\Controllers\Admin\StepController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/step\/all","name":"admin.step.all","action":"App\Http\Controllers\Admin\StepController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/step\/create","name":"admin.step.create","action":"App\Http\Controllers\Admin\StepController@create"},{"host":null,"methods":["POST"],"uri":"admin\/step","name":"admin.step.store","action":"App\Http\Controllers\Admin\StepController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/step\/{stepName}\/edit","name":"admin.step.edit","action":"App\Http\Controllers\Admin\StepController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/step\/{stepName}","name":"admin.step.update","action":"App\Http\Controllers\Admin\StepController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/step\/{stepName}","name":"admin.step.get","action":"App\Http\Controllers\Admin\StepController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/step\/{stepName}","name":"admin.step.delete","action":"App\Http\Controllers\Admin\StepController@delete"},{"host":null,"methods":["DELETE"],"uri":"admin\/step-item\/{step}","name":"admin.step-item.delete-item","action":"App\Http\Controllers\Admin\StepController@deleteItem"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/tip","name":"admin.tip.index","action":"App\Http\Controllers\Admin\TipController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/tip\/all","name":"admin.tip.all","action":"App\Http\Controllers\Admin\TipController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/tip\/create","name":"admin.tip.create","action":"App\Http\Controllers\Admin\TipController@create"},{"host":null,"methods":["POST"],"uri":"admin\/tip","name":"admin.tip.store","action":"App\Http\Controllers\Admin\TipController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/tip\/{tip}\/edit","name":"admin.tip.edit","action":"App\Http\Controllers\Admin\TipController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/tip\/{tip}","name":"admin.tip.update","action":"App\Http\Controllers\Admin\TipController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/tip\/{tip}","name":"admin.tip.get","action":"App\Http\Controllers\Admin\TipController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/tip\/{tip}","name":"admin.tip.delete","action":"App\Http\Controllers\Admin\TipController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order","name":"admin.order.index","action":"App\Http\Controllers\Admin\OrderController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order\/all","name":"admin.order.all","action":"App\Http\Controllers\Admin\OrderController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order\/{order}\/barcode","name":"admin.order.barcode","action":"App\Http\Controllers\Admin\OrderController@barcode"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order\/create","name":"admin.order.create","action":"App\Http\Controllers\Admin\OrderController@create"},{"host":null,"methods":["POST"],"uri":"admin\/order","name":"admin.order.search","action":"App\Http\Controllers\Admin\OrderController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order\/{order}\/edit","name":"admin.order.edit","action":"App\Http\Controllers\Admin\OrderController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/order\/{order}","name":"admin.order.update","action":"App\Http\Controllers\Admin\OrderController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order\/{order}","name":"admin.order.get","action":"App\Http\Controllers\Admin\OrderController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/order\/{order}","name":"admin.order.delete","action":"App\Http\Controllers\Admin\OrderController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order-status","name":"admin.order-status.index","action":"App\Http\Controllers\Admin\OrderStatusController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order-status\/all","name":"admin.order-status.all","action":"App\Http\Controllers\Admin\OrderStatusController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order-status\/create","name":"admin.order-status.create","action":"App\Http\Controllers\Admin\OrderStatusController@create"},{"host":null,"methods":["POST"],"uri":"admin\/order-status","name":"admin.order-status.store","action":"App\Http\Controllers\Admin\OrderStatusController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order-status\/{status}\/edit","name":"admin.order-status.edit","action":"App\Http\Controllers\Admin\OrderStatusController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/order-status\/{status}","name":"admin.order-status.update","action":"App\Http\Controllers\Admin\OrderStatusController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/order-status\/{status}","name":"admin.order-status.get","action":"App\Http\Controllers\Admin\OrderStatusController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/order-status\/{status}","name":"admin.order-status.delete","action":"App\Http\Controllers\Admin\OrderStatusController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/comment","name":"admin.comment.index","action":"App\Http\Controllers\Admin\CommentController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/comment\/all","name":"admin.comment.all","action":"App\Http\Controllers\Admin\CommentController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/comment\/create","name":"admin.comment.create","action":"App\Http\Controllers\Admin\CommentController@create"},{"host":null,"methods":["POST"],"uri":"admin\/comment","name":"admin.comment.store","action":"App\Http\Controllers\Admin\CommentController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/comment\/{comment}\/edit","name":"admin.comment.edit","action":"App\Http\Controllers\Admin\CommentController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/comment\/{comment}","name":"admin.comment.update","action":"App\Http\Controllers\Admin\CommentController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/comment\/{comment}","name":"admin.comment.get","action":"App\Http\Controllers\Admin\CommentController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/comment\/{comment}","name":"admin.comment.delete","action":"App\Http\Controllers\Admin\CommentController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/callback","name":"admin.callback.index","action":"App\Http\Controllers\Admin\CallbackController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/callback\/all","name":"admin.callback.all","action":"App\Http\Controllers\Admin\CallbackController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/callback\/create","name":"admin.callback.create","action":"App\Http\Controllers\Admin\CallbackController@create"},{"host":null,"methods":["POST"],"uri":"admin\/callback","name":"admin.callback.store","action":"App\Http\Controllers\Admin\CallbackController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/callback\/{callback}\/edit","name":"admin.callback.edit","action":"App\Http\Controllers\Admin\CallbackController@edit"},{"host":null,"methods":["PATCH"],"uri":"admin\/callback\/{callback}","name":"admin.callback.update","action":"App\Http\Controllers\Admin\CallbackController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/callback\/{callback}","name":"admin.callback.get","action":"App\Http\Controllers\Admin\CallbackController@get"},{"host":null,"methods":["DELETE"],"uri":"admin\/callback\/{callback}","name":"admin.callback.delete","action":"App\Http\Controllers\Admin\CallbackController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/notification","name":"admin.notification.index","action":"App\Http\Controllers\Admin\NotificationController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/notification\/all","name":"admin.notification.all","action":"App\Http\Controllers\Admin\NotificationController@getAll"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/notification\/{notification}\/view","name":"admin.notification.view","action":"App\Http\Controllers\Admin\NotificationController@view"},{"host":null,"methods":["POST"],"uri":"admin\/notification\/read","name":"admin.notification.read","action":"App\Http\Controllers\Admin\NotificationController@read"},{"host":null,"methods":["DELETE"],"uri":"admin\/notification\/{notification}","name":"admin.notification.delete","action":"App\Http\Controllers\Admin\NotificationController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"admin\/setting","name":"admin.setting.index","action":"App\Http\Controllers\Admin\SettingController@index"},{"host":null,"methods":["POST"],"uri":"admin\/setting","name":"admin.setting.store","action":"App\Http\Controllers\Admin\SettingController@store"},{"host":null,"methods":["POST"],"uri":"admin\/setting\/modify","name":"admin.setting.modify","action":"App\Http\Controllers\Admin\SettingController@modify"},{"host":null,"methods":["PATCH"],"uri":"admin\/setting\/{setting}","name":"admin.setting.update","action":"App\Http\Controllers\Admin\SettingController@update"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // Router.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // Router.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // Router.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // Router.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // Router.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // Router.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.Router = laroute;
    }

}).call(this);

