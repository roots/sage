# SageWoo - The ultimate boilerplate
A Sage fork ready for Woocommerce, with Bootstrap and automation in common tasks. Stop being a robot by customising common templates again & again and make your life easier with this boilerplate.


## Why?
Despite the organised backend code and the abstraction on top of abastraction, the Woocommerce frontend has many issues. Its been made for Woocommerce default styling, has difficult markup, countless classes and name collisions with the Bootstrap framework (`col-1`, `form-row` classes etc). Many of its classes are not name spaced and I had issues a few times with that. I need more flexibility on my workflow. Also with the new Sage 9, to setup Woocommerce it's a pain in the ass and the amount of boilerplate code it's big and confusing.

> In this boilerplate, I didn't use the `woocommerce.blade.php` with the `App\template('woocommerce')` method. I just override `single-product` and `archive-product` with blade ones. So now the controller works flawlessly on top of them, and we can have total flexibility on markup.

## ⚠️⚠️ This isn't the usual Wordpress template!
This template is **NOT** for non-developers. It doesn't have any styling! Each one component has the least minimal design for flexibility reasons.

This boilerplate uses Sage 9 starter theme, which in turn provides the latest best practices in PHP world (eg composer, Blade Template Engine, Webpack etc)
This is NOT for classic Wordpress developers who prefer plain PHP or obsolete JS. Everything here is highly coupled with Sage 9 and modern practices. 

Sage 9 and Bedrock made me stay in the Wordpress/PHP world for website projects and I strongly recommend it to you. Your workflow speed will increase at unimaginable levels.


## What's in the box?

* [Some screenshots](https://i.imgur.com/6z97MWT.jpg)
* Woocommerce support out of the box with working Controller (for `single-product`, `archive-product`)
* Radio/Select switcher for product options!
* Decluttered markup in common templates for best flexibility (eg checkout, single product, archive-product etc)
* Whenever the markup is dangerous to change (like common updated templates or very difficult to read code), a graceful css styling provided (eg the `form-pay.php`)
* Responsive!
* Organised SCSS code
* Namespaced classes `sw-**` for rapid styling
* BEM methodology for very very clean SCSS code

## Caveats

* Controller is not working inside woocommerce templates which are not included with blade (eg `form-billing.blade.php`)
* System status on woocommerce settings doesn't show override templates. If you know a fix, it would be awesome!
* In the scss files you'll see some very, VERY dirty code. It's like that because of the graceful reset styling on vanilla Woo markup


## Installation

### Instructions

```shell
cd themes-directory
git clone https://github.com/hambos22/sage-woo.git theme-name
cd theme-name
yarn
composer install
composer run-script sage-woo-setup
```

### Some notes
I extended `Roots\Sage\Installer\ComposerScript` and modified it a little bit to disable Bootstrap initialisation, keeping everything else intact. The `composer run-script sage-woo-setup` gives you the ability to set via cli the theme's credentials as Sage does. I was thinking to make a shell script for those simple operations but I prefer to use the official way for future compatibility reasons

You can find it here:

```shell
app
├── SageWoo
│   └── SageInstallerMod.php
```

Also, I have disabled the style lint because is very annoying. If you want it enabled you can uncomment the relevant code in webpack config.

### Todo

- [x] Single Product
- [x] Archive Product
- [x] Breadcrumbs
- [x] Radio Buttons
- [x] Notices
- [x] Forms
- [x] Cart
- [x] Checkout
- [x] Pagination
- [ ] Form validation
- [ ] Tabs<sup>*</sup>
- [ ] Sale badge (varies by design so on consideration)<sup>*</sup>
- [ ] Up-sells<sup>*</sup>
- [ ] Reviews<sup>*</sup>

<sup>*</sup>rarely used, on consideration, PRs are welcomed :)

## Templates
Current bladified woocommerce templates are the following. I always use custom markup on these. They are already filled up with their default actions so feel free to create awesomeness fast. You can find them at `resources/views/woocommerce`. Controller only works on `content-product` and `content-single-product` because they got included via Blade

```shell
├── cart
│   └── cart-empty.blade.php
├── checkout
│   ├── cart-errors.blade.php
│   ├── form-billing.blade.php
│   ├── form-checkout.blade.php
│   ├── form-coupon.blade.php
│   └── form-shipping.blade.php
├── content-product.blade.php
├── content-single-product.blade.php
├── global
│   ├── breadcrumb.blade.php
│   └── form-login.blade.php
├── myaccount
│   ├── form-edit-account.blade.php
│   ├── form-edit-address.blade.php
│   ├── form-login.blade.php
│   ├── form-lost-password.blade.php
│   ├── form-reset-password.blade.php
│   ├── lost-password-confirmation.blade.php
│   ├── my-account.blade.php
│   ├── my-address.blade.php
│   └── navigation.blade.php
├── notices
│   └── error.blade.php
├── order
│   └── order-details-customer.blade.php
├── single-product
│   └── add-to-cart
│       └── variable.blade.php
└── sw-components
    └── woo-radio.blade.php
``` 


## YAML settings
I've added some common configs into a YAML file. From there you can easily enable/disable features like Google Analytics and basic hooks and actions manipulation! Look bellow for more info.

## Misc Settings
You can tweak some usual settings easily by using sage-woo.yml

```yaml
general:
	remove_woo_styles: true # removes all Woocommerce css files
	remove_woo_scripts: #removes selected woo scripts, if all provided everything will be dequeued 
		- handler
		- handler #etc 
	analytics_id: 'UA-xxxxxx-Y' # add analytics script on footer
```

<details>
<summary>Handlers reference</summary>

```
'wc_price_slider',
'wc-single-product',
'wc-add-to-cart',
'wc-cart-fragments',
'wc-checkout',
'wc-add-to-cart-variation',
'wc-single-product',
'wc-cart',
'wc-chosen',
'woocommerce',
'prettyPhoto',
'prettyPhoto-init',
'jquery-blockui',
'jquery-placeholder',
'fancybox',
'jqueryui',
'selectWoo',
```
</details>

## Single Product Settings
```yaml
single_product:
```

### Radio for product options in variable products!
One awesome feature of Sage Woo is the ability to use radio buttons instead of select boxes for product options in variable products! And of-course for selected attributes so you can have multiple form patterns! You can use the `views/woocommerce/sw-components/woo-radio.blade.php` for total customization and endless creative design!
To enable it 

```yaml
single_product:
	radio_variations:
		- slug
		- slug
```

How it works? Because I avoid tweaking core files or override functions, I decided to use an alternative method for rendering radio buttons. Behind the scenes DOMDocument parses the html of the `woocommerce_dropdown_variation_attribute_options_html` filter and using its values, renders the appropriate html. One thing which I couldn't interfere was the JS section. I was forced to fork the `add-to-cart-variation.js` so be careful on your woo updates! There is a high possibility to break things so test everything!

And also after each enable/disable don't forget to clear the cache!


## Actions & Hooks
You can add or remove actions easily by using sage-woo.yml
Use namespaces for your functions! `namespace\func_name`
The format is the following:

```yaml
actions:
	# 1st method
	remove: # selected actions
		hook_name:
			namespace\func_name: order
			namespace\func_name: order
			# etc..
		hook_name: all # if 'all' provided every action which belongs to this hook is being removed
	# 2nd method, if you want total customisation just remove all actions
	remove_all: # removes all actions from every hook
		template_name: true #currently only single_product and product_archive.

```
Example

```yaml
actions:
	remove:
		woocommerce_before_main_content:
			breadcrumb: 10
		woocommerce_archive_description: all 
```

Example 2

```yaml
actions:
	remove_all:
		single_product: true
```

### Reference
Every action here has usually the prefix `woocommerce_` except those who starts with `WC` or `wc`. I put them without prefixes for better readability. Numbers on the right are the order.

<details>
<summary>Product Archive</summary>

* `before_main_content`

```
'output_content_wrapper' - 10
'breadcrumb' - 20
'WC_Structured_Data::generate_website_data()' - 30
```

* `archive_description`

```
'taxonomy_archive_description' - 10
'product_archive_description' - 10
```
 

* `before_shop_loop`

```
wc_print_notices - 10
result_count - 20
catalog_ordering - 30
```

* `shop_loop`

```
'WC_Structured_Data::generate_product_data()' - 10

```

* `after_shop_loop`

```
'pagination' - 10
```

* `no_products_found`

```
'no_products_found' - 10
```

* `before_shop_loop_item`

```
'template_loop_product_link_open' - 10
```

* `before_shop_loop_item_title`

```
'show_product_loop_sale_flash' - 10,
'template_loop_product_thumbnail' - 10
```

* `shop_loop_item_title`

```
'template_loop_product_title' - 10
```

* `after_shop_loop_item_title`

```
'template_loop_rating' - 5,
'template_loop_price' - 10
```

* `after_shop_loop_item`

```
'template_loop_product_link_close' - 5,
'template_loop_add_to_cart' - 10
```
</details>
<details>
<summary>Single Product</summary>

* `before_main_content`

```
'output_content_wrapper' - 10,
'breadcrumb' - 20,
```

* `after_main_content`

```
'output_content_wrapper_end' - 10
```

* `sidebar`

```
'get_sidebar' - 10
```

* `before_single_product_summary`

```
'show_product_sale_flash' - 10,
'show_product_images' - 20
```

* `single_product_summary`

```
'template_single_title' - 5,
'template_single_rating' - 10,
'template_single_price' - 10,
'template_single_excerpt' - 20,
'template_single_add_to_cart' - 30,
'template_single_meta' - 40,
'template_single_sharing - 50',
'WC_Structured_Data::generate_product_data()' - 60
```

* `after_single_product_summary`

```
'output_product_data_tabs' - 10,
'upsell_display' - 15,
'output_related_products - 20'
```
</details>



