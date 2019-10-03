.PHONY: composer-install up ps influxdb retention downsample restart down watch-client scale-up scale-down cluster-up cluster-down

composer-install:
	@docker run --rm -i -v "${PWD}":/app -w /app --user $(id -u):$(id -g) composer:1.8.3 install --ignore-platform-reqs --no-scripts

up:
	@docker-compose up -d

ps:
	@docker-compose ps

influxdb:
	@docker-compose exec influxdb influx -database metrics

restart:
	@docker-compose restart

down:
	@docker-compose down

watch-client:
	@docker-compose logs -f client

scale-up:
	@docker-compose up -d --scale client=${clients}

scale-down:
	@docker-compose up -d --scale client=1

cluster-up:
	@docker-compose -f cluster/docker-compose.yaml up -d

cluster-down:
	@docker-compose -f cluster/docker-compose.yaml down

cluster-scale-up:
    @docker-compose -f cluster/docker-compose.yaml up -d --scale client=${clients}

cluster-scale-down:
    @docker-compose -f cluster/docker-compose.yaml --scale client=1

docker-ps:
	@docker ps | awk '{print $$NF}' | tail -n+2
