.PHONY: composer-install up ps influxdb retention downsample restart down watch-client scale-up scale-down
composer-install:
	docker run --rm -i -v "${PWD}":/app -w /app --user $(id -u):$(id -g) composer:1.8.3 install --ignore-platform-reqs --no-scripts

up:
	docker-compose up -d

ps:
	docker-compose ps

influxdb:
	docker-compose exec influxdb influx -database metrics

restart:
	docker-compose restart

down:
	docker-compose down

watch-client:
	docker-compose logs -f client

scale-up:
	docker-compose up -d --scale client=${clients}

scale-down:
	docker-compose up -d --scale client=1
