# Metrics Demo

## Usage

- Start the containers: `docker-compose up -d`
- Application: http://localhost:8080
- Chronograf: http://localhost:8888
- Grafana: http://localhost:3000


## Application

### ToDo list manager

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

## TODO

- Refactor actions
- Validation
- Exception handling
- API client (without any client-side validation)
- API tests
- Unit tests

## Instrumentation

- Metrics reporting service
- HTTP response status metrics
- PSR middleware for HTTP response status metrics
- Response time metrics
- PSR middleware for response time metrics
- Business metrics (total/minute):
    - registrations
    - logins
    - created lists
    - created tasks
    - done tasks
- Infrastructure metrics:
    - nginx requests/second
    - nginx connections
    - mysql queries per second
    - mysql min/max/avg/perc. operation time
    - mysql index usage stats
    - redis operations per second
    - redis key space size
    - redis ttl stats
    - php-fpm active workers
    - nic traffic
    - r/w disk ops
    - memory
    - cpu
    
## Scenarios

1. Increase number of clients.
2. Decrease/increase wait time between client actions.
3. Decrease/increase number of php-fpm workers.
4. Decrease/increase nginx connections.
5. Drop/add mysql index.
6. Flush redis.
7. Decrease curl connection timeout on client.
8. Decrease curl operation timeout on client.
9. Add sleep in server index.php.
 