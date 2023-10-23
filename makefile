TARGETS=src tests/unit
PHP_STAN=php -d memory_limit=300M vendor/bin/phpstan analyse $(TARGETS) -c phpstan.neon --level 8
PHP_CS_SETTINGS=--standard=./vendor/gamee/php-code-checker-rules/ruleset.xml --extensions=php --ignore=temp
TEST_UNIT=XDEBUG_MODE=coverage php vendor/bin/codecept run unit --coverage-cobertura

.PHONY: stan
stan:
	$(PHP_STAN)

.PHONY: stan-ci
stan-ci:
	$(PHP_STAN) --no-progress

.PHONY: cs
cs:
	vendor/bin/phpcs $(PHP_CS_SETTINGS) -sp $(TARGETS) --parallel=8

.PHONY: csfix
csfix:
	vendor/bin/phpcbf $(PHP_CS_SETTINGS) -sp $(TARGETS)

.PHONY: unit
unit:
	$(TEST_UNIT) --no-exit --verbose

.PHONY: unit-ci
unit-ci:
	$(TEST_UNIT)