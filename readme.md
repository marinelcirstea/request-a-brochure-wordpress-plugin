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

### Structure
```
request-a-brochure.php => Main entry file
```
```
assets => Admin and user-facing scripts
```
```
inc/frontend-form-shortcode.php => User-facing front-end
```
```
inc/admin-page => Admin facing front-end
```
```
inc/api/rab-router.php => Rest API routes
```
```
inc/api/rab-brochure-controller.php => Brochures controller
```
```
inc/api/rab-brochure-request-controller.php => Brochure requests controller
```
```
inc/api/rab-service.php => Brochure + Brochure requests service
for working with the database
```
