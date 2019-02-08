# Metrics Demo

## Usage

- Start the containers: `docker-compose up -d`
- Application: http://localhost:8080
- Chronograf: http://localhost:8888
- Grafana: http://localhost:3000


## Application

### ToDo list manager

Functions:
1. users/tokens:
   - signup(username, password) -> token
   - get_token(username, password) -> token
   - use_token(token, ttl) (internal on each action with token)
     -> true (it exists and ttl was updated)
     -> false (it doesn't exist, probably already expired -> get_token) 
   - get_user(token) -> user (internal function)
2. lists:
   - get_many(token) -> list[]
   - get_one(token, id) -> list
   - create(token, name) -> id
   - update(token, id, name)
   - delete(token, id)
3. tasks:
   - get_many(token, list_id) -> task[]
   - create(token, list_id, summary) -> task
   - done(token, task_id)
   - undone(token, task_id)
   - delete(token, task_id)

### Client

Possible actions:
- Signup
- Get lists
- Create list
- Get list
- Add task
- Mark task as done
- Mark task as undone
- Delete task
- Delete list
- Wait
- Delete token
- Get token

Scenarios:
- TODO: create a state machine with random (weighted) transitions
- Generate a thousand of static scenarios from the state machine
- Store scenarios
- Load scenario, play scenario

Simulation:
- Chance of new user
- Chance of existing user
- Store user credentials in shared client memory for logins 