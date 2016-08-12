
# Big Room Studios - Wordpress Starter Theme

Forked at version 8.4.2 from https://roots.io/sage/. Thank you Roots and Sage team for your awesome work!

## Starting a Custom Theme Project

* Setup a Wordpress environment, whether it be local or on a server.
* Create a new folder `$ mkdir Your-Project-Name`
* Change to the new directory `$ cd Your-Project-Name`
* Clone the BRS Fork `$ git clone git@github.com:BigRoomStudios/sage.git .`
* Create Github repository
* Set the Github remote address to the new repository `$ git remote set-url origin git@github.com:BigRoomStudios/Your-Project-Name.git`
* Go to the README.md and update the header to Your-Project-Name\
* Run `$ npm install`
* Run `$ bower install`
* Run `$ gulp --production`
* Add changes `$ git add .`
* Make the initial commit `$ git commit -m "Initial commit"`
* Push that commit to the new repo `$ git push`

## Development Workflow

* For each template, or chunk of work create a branch `$ git checkout -b Your-Branch-Name`
* Add and commit your changes with a human readable description
* Push the repo up to Git `$ git push --set-upstream origin Your-Branch-Name`
* Create a pull request and add your peers to code review.

*Enjoy the amazing starting point and have fun developing the custom theme for your client!*

### Install the build tools

Building the theme requires [node.js](http://nodejs.org/download/). We recommend you update to the latest version of npm: `$ npm install -g npm@latest`.

From the command line:

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `$ npm install -g gulp bower`
2. Navigate to the theme directory, then run `$ npm install`
3. Run `$ bower install`

You now have all the necessary dependencies to run the build process.

Sage is a WordPress starter theme based on HTML5 Boilerplate, gulp, Bower, and Bootstrap Sass, that will help you make better themes.

* Homepage: [https://roots.io/sage/](https://roots.io/sage/)
* Documentation: [https://roots.io/sage/docs/](https://roots.io/sage/docs/)

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js 0.12.x  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |

For more installation notes, refer to the [Install gulp and Bower](#install-gulp-and-bower) section in this document.

## Theme development

Sage uses [gulp](http://gulpjs.com/) as its build system and [Bower](http://bower.io/) to manage front-end packages.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --production` — Compile assets for production (no source maps).

### Using BrowserSync

To use BrowserSync during `gulp watch` you need to update `devUrl` at the bottom of `assets/manifest.json` to reflect your local development hostname.

For example, if your local development URL is `http://project-name.dev` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://project-name.dev"
  }
...
```
If your local development URL looks like `http://localhost:8888/project-name/` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://localhost:8888/project-name/"
  }
...
```
