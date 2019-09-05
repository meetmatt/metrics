# Metrics Demo

## Usage

- Install dependencies: `make`
- Be aware that composer is configured to ignore file system changes. You must run `make` each time you create, move, rename or delete files in `src`.
- Start the containers: `docker-compose up -d`
- Watch the client using the app: `docker-compose logs -f client`
- Open Grafana and add dashboards: http://localhost:3000
- [Sample dashboard](./docker/grafana/dashboards/API%20metrics-1550251129042.json)
- Scale client application to 10 instances and watch the changes on the dashboard: `docker-compose up -d --scale client=10`
- Add more instrumentation

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
 
