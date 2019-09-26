# Metrics Demo

## Usage

- Install dependencies: `make`
- Be aware that composer is configured to ignore file system changes. You must run `make` each time you create, move, rename or delete files in `src`.
- Start the containers: `docker-compose up -d`
- Watch the client using the app: `docker-compose logs -f client`
- Open Grafana http://localhost:3000 (credentials = admin/admin)
- Add data source (Configuration -> Data Sources -> Add Data Source): URL = http://influxdb:8086, db = `metrics`
- Add dashboard (New Dashboard -> Import Dashboard -> Upload .json file) from `docker/grafana/dashboards`
- Scale client application to 10, 20, 40 instances and watch the changes on the dashboard: `make scale-up clients=10`
(`make scale-down` for downgrading the load back to 1 client again)
- watch all the containers live with `ctop`:
```bash
docker run -it --name ctop --rm -v /var/run/docker.sock:/var/run/docker.sock wrfly/ctop:latest
```

## Application

### ToDo list manager

See `test/support/todo.http`.

- Registration
- Login
- Get all lists
- Get one list
- Create list
- Delete list
- Get tasks
- Create task
- Mark task as done
- Mark task as not done
- Delete task

## TODO: Instrumentation

- HTTP response status metrics
- Response time metrics
- Business metrics (total/minute):
    - registrations
    - logins
    - created lists
    - created tasks
    - done tasks
    
## Degradation scenarios

1. Decrease/increase number of clients.
2. Decrease/increase wait time between client actions.
3. Decrease/increase number of php-fpm workers.
4. Decrease/increase size of nginx connection pool.
5. Drop/add mysql index.
6. Flush redis.
7. Decrease curl connection timeout on client.
8. Decrease curl operation timeout on client.
9. Add sleep in server index.php.

## Redundant infrastructure

See [cluster](cluster)

### System diagram

```
+-----------------------------+   +-----------------------------+
|                             |   |                             |
|  Zone 0                     |   |   Zone 1                    |
|                             |   |                             |
|   +-----+ +-----+ +-----+   |   |   +-----+ +-----+ +-----+   |
|   | App | | App | | App |   |   |   | App | | App | | App |   |
|   +--+--+ +--+--+ +--+--+   |   |   +--+--+ +--+--+ +--+--+   |
|       \      |      /       |   |       \      |      /       |
|        \     |     /        |   |        \     |     /        |
|      +--v----v----v--+      |   |      +--v----v----v--+      | 
|      | Load Balancer |      |   |      | Load Balancer |      |
|      +----+------+---+      |   |      +---+------+----+      |
|          /        .         |   |         /        .          |
|         /          .        |   |        /          .         |
|  +-----v----+ +-----v----+  |   |  +----v-----+ +----v-----+  |
|  |   Main   | |  Backup  |  |   |  |   Main   | |  Backup  |  |
|  | Telegraf | | Telegraf |  |   |  | Telegraf | | Telegraf |  |
|  +-----+----+ +-----+----+  |   |  +----+-----+ +----+-----+  |   
|        |            .       |   |       |            .        |
+--------|------------.-------+   +-------|------------.--------+        
         |            .                   |            .        
+--------|------------.-------------------|------------.--------+
|        |            .                   |            .        |                   
|        |        +---v-------------------v---+        .        |
|        |        |                           |        .        |
|        +-------->   Ingress Load Balancer   <. . . . +        |
|                 |                           |                 |
|                 +----------+-----+----------+                 |
|                           /       \                           |
|  Ingress                 /         \                          |
|                     +---v---+   +---v---+                     |
|                     | Relay |   | Relay |                     |
|                     +--+--+-+   +-+--+--+                     |
|                        |   \     /   |                        |
|                        |    \   /    |                        |
|                        |     \ /     |                        |
|                        |      X      |                        |
|                        |     / \     |                        |
|                        |    /   \    |                        |
|                        |   /     \   |                        |
|                +-------v--v-+   +-v--v-------+                |
|                |            |   |            |                |
|                |   Influx   |   |   Influx   |                |
|                |            |   |            |                |
|                +--------+---+   +---+--------+                |
|                          \         /                          |
|                           \       /                           |
|                    +-------v-----v--------+                   |
|                    |  Query Load Balancer |                   |
|                    +----------------------+                   |
|                               ^                               |
|                               |                               |
+-------------------------------|-------------------------------+
                                |
                  +-------------|------------+
                  | Dashboard   |            |
                  |             |            |
                  |       +-----+-----+      |
                  |       |  Grafana  |      |
                  |       +-----------+      |
                  |                          |
                  +--------------------------+
```


### Configuration

#### Grafana

Grafana provisioning configuration:

```yaml
apiVersion: 1

datasources:
  - name: Metrics
    type: influxdb
    access: proxy
    database: metrics
    user: influxdb
    password: influxdb
    url: http://query-load-balancer:80
    jsonData:
      httpMode: GET
```