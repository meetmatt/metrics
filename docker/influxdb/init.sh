#!/usr/bin/env bash
influx < /docker-entrypoint-initdb.d/retention.sql
influx -database 'metrics' < /docker-entrypoint-initdb.d/downsampling.sql
