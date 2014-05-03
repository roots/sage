/*global window*/
/*global angular*/
/*global ___socket___*/
(function (window, socket) {

    "use strict";

    var app = angular.module("BrowserSync", []);

    /**
     * Socket Factory
     */
    app.service("Socket", function () {
        return {
            addEvent: function (name, callback) {
                socket.on(name, callback);
            },
            removeEvent: function (name, callback) {
                socket.removeListener(name, callback);
            }
        };
    });


    /**
     * Options Factory
     */
    app.service("Options", function () {
        return {

        };
    });

    /**
     * Main Ctrl
     */
    app.controller("MainCtrl", function ($scope, removeCpFilter, Socket) {

        $scope.options = false;
        $scope.browsers = [];
        $scope.socketId = "";

        $scope.ui = {
            snippet: false
        };

        /**
         * @type {{connection: connection, addBrowsers: addBrowsers}}
         */
        $scope.socketEvents = {
            connection: function (options) {
                var _this = this;
                $scope.$apply(function () {
                    if (_this.socket) {
                        $scope.socketId = _this.socket.sessionid;
                    }
                    $scope.options = options;
                });
            },
            addBrowsers: function (browsers) {
                $scope.$apply(function () {
                    $scope.browsers = removeCpFilter(browsers, $scope.socketId);
                });
            }
        };

        /**
         *
         */
        $scope.toggleSnippet = function () {
            $scope.ui.snippet = !$scope.ui.snippet;
        };

        /**
         * @param url
         */
        $scope.goTo = function (url) {
            socket.emit("cp:goTo", {url: url});
        };

        Socket.addEvent("connection", $scope.socketEvents.connection);
        Socket.addEvent("cp:browser:update", $scope.socketEvents.addBrowsers);
    });

    /**
     * URL info header
     */
    app.directive("urlInfo", function () {
        return {
            restrict: "E",
            scope: {
                options: "="
            },
            template: "<h1><small>{{type}} running at: </small><a href=\"{{url}}\" target='_blank'>{{url}}</a></h1>",
            controller: function ($scope) {
                $scope.url = $scope.options.url;
                $scope.type = $scope.options.server ? "Server" : "Proxy";
            }
        };
    });

    /**
     * Remove control panel from list of items
     */
    app.filter("removeCp", function () {
        return function (items, id) {
            var filtered = [];
            items.forEach(function (item) {
                if (item.id !== id) {
                    filtered.push(item);
                }
            });
            return filtered;
        };
    });

}(window, (typeof ___socket___ === "undefined") ? {} : ___socket___));