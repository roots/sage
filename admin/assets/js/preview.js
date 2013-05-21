jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */

less = {
    env: "development", // or "production"
    async: false,       // load imports async
    fileAsync: false,   // load imports async when in a page under
                        // a file protocol
    poll: 1000,         // when in watch mode, time in ms between polls
    functions: {},      // user functions, keyed by name
    dumpLineNumbers: "comments", // or "mediaQuery" or "all"
    relativeUrls: false,// whether to adjust url's to be relative
                        // if false, url's are already relative to the
                        // entry less file
    rootpath: ":/a.com/"// a path to add on to the start of every url
                        //resource
};


jQuery(document).ready(function($){
/*
$("head").append('<style type="text/less">
h1 {color:red;}
p {color:blue;}
</style>
</head>');
console.log(lessData);
*/

function setHandler(name) {
    wp.customize( name , function( value ) {
        console.log('Setting customize: '+name);
        value.bind( function( to ) {
            console.log('Setting customize bind: '+name);
            var variable = '@'+name;
            //less.modifyVars({
            //    variable : '#5B83AD'
            //});
            //console.log(name+" - "+to);
        });
    });

}
    // Create the handlers
    for (var option in smofPost.variables) {
        if (smofPost.variables[option].less == true) {
                setHandler(smofPost.variables[option].id);
        }
    }




});