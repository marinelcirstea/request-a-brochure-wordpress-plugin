# WordPress setup with docker

### WordPress: latest

```yml
USER: user
PASSWORD: password
```

### MySQL: 5.7

```yml
ROOT_PASSWORD: rootpassword
USER: user
PASSWORD: password
```

### PHPMyAdmin:latest

```yml
USERNAME: root
PASSWORD: rootpassword 
```

## SETUP

#### You need [docker](https://www.docker.com) installed on your machine

### Start
```shell
docker-compose up -d
```

### Stop
```shell
docker-compose down
```
