[![Latest Stable Version](https://poser.pugx.org/thecodingmachine/phpstan-safe-rules/v/stable)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rules)
[![Total Downloads](https://poser.pugx.org/thecodingmachine/phpstan-safe-rules/downloads)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rules)
[![Latest Unstable Version](https://poser.pugx.org/thecodingmachine/phpstan-safe-rules/v/unstable)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rules)
[![License](https://poser.pugx.org/thecodingmachine/phpstan-safe-rules/license)](https://packagist.org/packages/thecodingmachine/phpstan-safe-rules)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/phpstan-safe-rules/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thecodingmachine/phpstan-safe-rules/?branch=master)
[![Build Status](https://travis-ci.org/thecodingmachine/phpstan-safe-rules.svg?branch=master)](https://travis-ci.org/thecodingmachine/phpstan-safe-rules)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/phpstan-safe-rules/badge.svg?branch=master&service=github)](https://coveralls.io/github/thecodingmachine/phpstan-safe-rules?branch=master)


PHPStan rules for thecodingmachine/safe
=======================================

The [thecodingmachine/safe](https://github.com/thecodingmachine/safe) package provides a set of core PHP functions rewritten to throw exceptions instead of returning `false` when an error is encountered.

This PHPStan rule will help you detect unsafe function call and will propose you to use the `thecodingmachine/safe` variant instead.

Please read [thecodingmachine/safe documentation](https://github.com/thecodingmachine/safe) for details about installation and usage.
