.PHONY: composer-install up ps influxdb retention downsample restart down watch-client scale-up scale-down cluster-network cluster-center-up cluster-west-up cluster-east-up cluster-up cluster-down

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

cluster-network:
	@docker network create metrics-cluster.wan || true

cluster-center-up:
	@docker-compose -f cluster/docker-compose-center.yaml up -d

cluster-west-up:
	@docker-compose -f cluster/docker-compose-west.yaml up -d

cluster-east-up:
	@docker-compose -f cluster/docker-compose-east.yaml up -d

cluster-up: cluster-network cluster-center-up cluster-west-up cluster-east-up

cluster-down:
	@docker-compose -f cluster/docker-compose-east.yaml down
	@docker-compose -f cluster/docker-compose-west.yaml down
	@docker-compose -f cluster/docker-compose-center.yaml down

docker-ps:
	@docker ps | awk '{print $$NF}' | tail -n+2
