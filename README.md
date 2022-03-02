
# ToDo Manager

Sample ToDo Manager build with Symfony5

## Run Locally

Clone the project

```bash
  git clone git@github.com:naskoff/todo-manager.git todo-manager
```

Go to the project directory

```bash
  cd todo-manager
```

Start docker containers

```bash
  docker-compose up -d 
```

Run script init-db for creating and managing database
```bash
  docker-compose exec php composer run init-db
```

Now you can open link http://localhost:8080 and login with admin:admin
