.PHONY: composer-install
composer-install:
	docker run --rm -i -v "${PWD}":/app -w /app --user $(id -u):$(id -g) composer:1.8.3 install --ignore-platform-reqs --no-scripts
