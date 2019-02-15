.PHONY: composer-install
composer-install:
	docker run --rm -i -v "${PWD}":/app -w /app --user $(id -u):$(id -g) composer:1.8.3 install --ignore-platform-reqs --no-scripts

.PHONY: up
up:
	docker-compose up -d

.PHONY: down
down:
	docker-compose down

.PHONY: watch-client
watch-client:
	docker-compose logs -f client

.PHONY: scale-up
scale-up:
	docker-compose up -d --scale client=10

.PHONY: scale-down
scale-down:
	docker-compose up -d --scale client=1
