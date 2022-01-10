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
note: it takes a while when you run it for the first time. Be patient

### Stop
```shell
docker-compose down
```

### Usage
```
Shortcode: [rab_form]
```

#### Access

##### Admin
```
All API routes except for /brochure-requests
```

##### Anyone
```
Only /brochure-requests
```
