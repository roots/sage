lessphp uses [phpunit](https://github.com/sebastianbergmann/phpunit/) for it's tests

`InputTest.php` iterates through all the less files in `inputs/`, compiles them,
then compares the result with the respective file in `outputs/`.

From the root you can run `make` to run all the tests.

## bootstrap.sh

Clones twitter bootsrap, compiles it with lessc and lessphp, cleans up results
with sort.php, and outputs diff. To run it, you need to have git and lessc
installed.

