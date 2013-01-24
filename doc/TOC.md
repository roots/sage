[Roots Theme homepage](http://www.rootstheme.com/)

# Roots Theme documentation

Roots is a starting WordPress theme made for developers that’s based on [HTML5 Boilerplate](http://html5boilerplate.com/) and [Bootstrap from Twitter](http://twitter.github.com/bootstrap/).

## Installation

* Roots is a standard wordpress theme that works with late-ish versions of wordpress. Read the [wordpress themes guide](https://codex.wordpress.org/Using_Themes) if you do not know how to install wordpress themes.
* Clone the git repo - `git clone git://github.com/retlehs/roots.git` - or [download it](https://github.com/retlehs/roots/zipball/master)
* Reference the [theme activation](activation.md) documentation to understand everything that happens once you activate Roots. If you are starting a new site, the defaults usually make sense.

## Configuration

Edit `lib/config.php` to enable or disable support for various theme functions and to define constants that are used throughout the theme. For instance, this file will determine when and where the sidebar shows up, and this is where you put your Google Analytics ID

Edit `lib/init.php` to setup custom navigation menus and post thumbnail sizes.


## Getting started

* [Usage](usage.md) — Overview of the project contents.
* [FAQ](faq.md) — Frequently asked questions, along with their answers.

## The core of Roots
* [Theme library](lib.md) — A guide to the `lib/` directory which contains all of the theme functionality, including: [theme activation](activation.md), the [theme wrapper](wrapper.md), [clean up](cleanup.md), and [rewrites](rewrites.md).
* [Theme templates](templates.md) — A guide to the `templates/` directory which contains all of the theme templates.

## Development

* [Contributing to Roots](/retlehs/roots/blob/master/CONTRIBUTING.md) — Guidelines on how to contribute effectively.
* [Extending and customizing Roots](extend.md) — Going further with Roots.
