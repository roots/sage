## Contributing
 * Fork the project.
 * Make your feature addition or bug fix.
 * Add documentation if necessary. 
 * Add tests for it. This is important so I don't break it in a future version unintentionally.
 * Send a pull request. Bonus points for topic branches.

## Reporting Sass compilation and syntax issues

The [libsass] library is not currently at feature parity with the 3.2 [Ruby Gem](https://github.com/nex3/sass) that most Sass users will use, and has little-to-no support for 3.3 syntax. While we try our best to maintain feature parity with [libsass], we can not enable features that have not been implemented in [libsass] yet.

If you'd like to see what features are still upcoming in [libsass], [Jo Liss](http://twitter.com/jo_liss) has written [a blog post on the subject](http://www.solitr.com/blog/2014/01/state-of-libsass/).

Please check for [issues on the libsass repo](https://github.com/hcatlin/libsass/issues) (as there is a good chance that it may already be an issue there for it), and otherwise [create a new issue there](https://github.com/hcatlin/libsass/issues/new).

If this project is missing an API or command line flag that has been added to [libsass], then please open an issue here. We will then look at updating our [libsass] submodule and create a new release. You can help us create the new release by rebuilding binaries, and then creating a pull request to the [node-sass-binaries](https://github.com/andrew/node-sass-binaries) repo.

[libsass]: https://github.com/hcatlin/libsass
