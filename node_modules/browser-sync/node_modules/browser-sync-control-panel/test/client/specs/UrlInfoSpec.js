describe("Directive: Tabs", function () {

    var scope, element, compile;
    beforeEach(module("BrowserSync"));

    // Initialize the controller and a mock scope
    beforeEach(inject(function ($compile, $rootScope) {
        scope = $rootScope;
        compile = $compile;
    }));

    describe("Rendering the top title bar with URL info for server", function () {
        beforeEach(function () {

            // Set the user on the parent scope to simulate how it'd happen in your app
            scope.options = {
                server: {
                    baseDir: "./"
                },
                url: "http://0.0.0.0:3002"
            };

            // Pass in the user object to the directive
            element = angular.element("<url-info options=\"options\"></url-info>");

            // Compile & Digest as normal
            compile(element)(scope);
            scope.$digest();
        });

        // This test will fail as we're looking at the parent scope here & not the directives' 'isolated' scope.
        it("should render the correct text with server", function () {
            var actual = element.text();
            var expected = "Server running at: http://0.0.0.0:3002";
            assert.equal(actual, expected);
        });
    });
    describe("Rendering the top title bar with URL info for proxy", function () {
        beforeEach(function () {

            // Set the user on the parent scope to simulate how it'd happen in your app
            scope.options = {
                proxy: {
                    host: "0.0.0.0"
                },
                url: "http://0.0.0.0:3002"
            };

            // Pass in the user object to the directive
            element = angular.element("<url-info options=\"options\"></url-info>");

            // Compile & Digest as normal
            compile(element)(scope);
            scope.$digest();
        });

        // This test will fail as we're looking at the parent scope here & not the directives' 'isolated' scope.
        it("should render the correct text with server", function () {
            var actual = element.text();
            var expected = "Proxy running at: http://0.0.0.0:3002";
            assert.equal(actual, expected);
        });
    });
});