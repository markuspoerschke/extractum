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
	@echo '  vendor              Installs composer dependencies'
	@echo '  test                Execute all tests'
	@echo '  fix                 Fixes composer.json and code style'
	@echo '  fix-prettier        Fix code style of non PHP files (not included in "fix" target)'

# Build
vendor:
	composer install --no-interaction

# Test
.PHONY: test
test: test-code-style test-psalm test-phpmd test-phpunit test-composer-normalize test-validate-composer

.PHONY: test-code-style
test-code-style: vendor
	php-cs-fixer fix --dry-run --diff

.PHONY: test-psalm
test-psalm: vendor
	psalm -m --no-progress ${PSALM_FLAGS}

.PHONY: test-phpunit
test-phpunit: vendor
	phpunit ${PHPUNIT_FLAGS}

.PHONY: test-validate-composer
test-validate-composer:
	composer validate

.PHONY: test-composer-normalize
test-composer-normalize: vendor
test-composer-normalize:
	composer normalize --dry-run --diff

.PHONY: test-phpmd
test-phpmd: vendor
test-phpmd:
	phpmd ./src text rulesets.xml

# Fix
.PHONY: fix
fix: fix-code-style fix-composer

.PHONY: fix-code-style
fix-code-style: vendor
fix-code-style:
	php-cs-fixer -- fix

.PHONY: fix-composer
fix-composer: vendor
fix-composer:
	composer normalize --no-update-lock
	composer update nothing

.PHONY: fix-prettier
fix-prettier:
	npx prettier@^2 . --write
