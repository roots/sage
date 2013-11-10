edd_extension
=============

Run by cloning to your ~/ReduxCore/extensions/ folder as `edd`.



To run this, you must add some args to your ReduxFramework instance. Here's an example:

```php
$args['edd'] = array(
	'mode' 			  => 'template', // template|plugin
	'path' 			  => '', // Path to the plugin/template main file
	'remote_api_url'  => 'http://shoestrap.org',    // our store URL that is running EDD
	'version'         => "2.0.1",  // current version number
	'item_name'       => "Shoestrap 3",      // name of this theme
	'author'          => "Aristeides Stathopoulos, Dimitris Kalliris, Dovy Paukstys",    // author of this theme
	'field_id'        => "edd", // ID of the field used by EDD
	);
```

Then you also need to specify a field of type edd, it's as simple as:

```php
array(
	'id'=>'edd',
	'type' => 'edd',
	'title' => __('EDD License', 'redux-framework-demo'), 
	'subtitle' => __('Please enter a valid key.', 'redux-framework-demo'),
	),
```

You can place that field anywhere. Now, be sure the field_id and the actual id of your EDD field are the same. That's very important.