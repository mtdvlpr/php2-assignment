# PHP2 Assignment
The PHP2 assignment of InHolland.

## Getting Started
This part helps you get started with the Haarlem Festival Project. Please follow it carefully, and don't hesitate to reach out if you have any questions.

### Prerequisites
To get started we expect you to have the following things installed:
- Node JS (14 or later) [Download Node](https://nodejs.org/en/)
- Docker engine v1.13 or higher. Your OS provided package might be a little old, if you encounter problems, do upgrade. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
- Docker compose v1.12 or higher. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

### Installing dependencies
For your first run you will need to install some dependencies, this should be pretty straight forward.

#### Installing the Node packages
Open your terminal and go to this directory, then type the following:

```sh
npm install
# Or...
yarn
```

Installing these packages might take a while, so please wait till this finishes.

#### Installing docker packages
Open your terminal and go to this directory, then type the following:

```sh
docker-compose up -d
```

## Running the project
You can run the project using the following commands:

## Node
```sh
# Start the development ENV for TS and SCSS
npm run dev
# Or...
yarn dev
```

This will watch for changes to the typescript and SCSS files, and automatically compile them.

## Docker
```sh
# Start the Web server (Starts the docker containers)
docker-compose up -d
```

You can access your application via **`localhost`**, if you're running the containers directly, or through **``** when run on a vm. nginx and mailhog both respond to any hostname, in case you want to add your own hostname on your `/etc/hosts`

Service|Address outside containers
------|---------
Webserver|[localhost:3000](http://localhost:3000)

### Hosts inside the environment
You'll need to configure your application to use any services you enabled:

Service|Hostname|Port number
------|---------|-----------
php-fpm|php-fpm|9000

### More information
For more information about the docker environment see [DOCKER.md](./DOCKER.md)

## Codebase
This is the codebase for the PHP2 assignment, this section contains more information in regards to the codebase.

### Techniques
For the Haarlem festival we use several techniques, the main ones are listed here:
- TypeScript
- SCSS
- PHP 8
- MYSQL 8

### Folder structure
```sh
haarlem-festival-php/
├── .env                # The environment variables for the back-end
├── .vscode             # Files related to the behavior of VSCode
├── webpack.config.js   # The build config for the project
├── phpdocker           # Docker container configs
├── src                 # Front-end code that needs to get compiled by webpack
  ├── scss              # The SCSS code of the application
  └── ts                # The TypeScript code of the application
├── controller        # The controller layer of the MVC pattern
├── db                # The database layer, responsible for interacting with the DB
├── model             # The model layer of the MVC pattern
├── public            # Files inside this folder get served to the browser
  ├── index.php       # The main file of the application
  ├── api             # API endpoints for the application
  └── assets          # Static resources such as JS, CSS and images
├──vendor             # Packages installed using composer.
└── views             # The View layer of the MVC pattern.
  └── components      # Components for the views
```

