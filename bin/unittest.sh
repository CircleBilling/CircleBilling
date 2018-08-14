#!/usr/bin/env bash

### Unit test for All ###
src/vendor/bin/phpunit

### Unit test for Library ###
src/vendor/bin/phpunit --testsuite Library