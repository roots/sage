jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */



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
        //console.log('Setting customize: '+name);
        value.bind( function( to ) {
            //console.log('Setting customize bind: '+name);
            var variable = '@'+name;
            console.log(variable);
            less.modifyVars({
                variable : to
            });
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