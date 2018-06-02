#!/usr/bin/env bash

### Unit test for All ###
src/bb-vendor/bin/phpunit

### Unit test for Library ###
src/bb-vendor/bin/phpunit --testsuite Library