MAKEFLAGS += --warn-undefined-variables
SHELL := bash
PATH := $(CURDIR)/vendor/bin:$(PATH)
PSALM_FLAGS ?=
PHPUNIT_FLAGS ?=
export XDEBUG_MODE = coverage

# Help
.PHONY: help
help:
	@echo 'Available targets:'
	@echo '  dependencies        Installs composer dependencies'
	@echo '  test                Execute all tests'
	@echo '  fix                 Fixes composer.json and code style'

# Build
.PHONY: dependencies
dependencies:
	composer install --no-interaction

# Test
.PHONY: test
test: test-code-style test-psalm test-phpmd test-phpunit test-composer-normalize test-validate-composer

.PHONY: test-code-style
test-code-style: dependencies
	php-cs-fixer fix --dry-run --diff

.PHONY: test-psalm
test-psalm: dependencies
	psalm -m --no-progress ${PSALM_FLAGS}

.PHONY: test-phpunit
test-phpunit: dependencies
	phpunit ${PHPUNIT_FLAGS}

.PHONY: test-validate-composer
test-validate-composer:
	composer validate

.PHONY: test-composer-normalize
test-composer-normalize: dependencies
test-composer-normalize:
	composer normalize --dry-run --diff

.PHONY: test-phpmd
test-phpmd: dependencies
test-phpmd:
	phpmd ./src text rulesets.xml

# Fix
.PHONY: fix
fix: fix-code-style fix-composer

.PHONY: fix-code-style
fix-code-style: dependencies
fix-code-style:
	php-cs-fixer -- fix

.PHONY: fix-composer
fix-composer: dependencies
fix-composer:
	composer normalize --no-update-lock
	composer update nothing
