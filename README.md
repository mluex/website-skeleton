# website-skeleton
## Introduction
This website skeleton uses Zend's service manager and their PSR-15 packages to integrate them into a quite small website starter package along withe league/router and league/plates.

It ships together with a pre-configured Docker environment for developing.

## Installation using Composer
To create your new project:

```bash
$ composer create-project mluex/website-skeleton path/to/project
```

Start your Docker development environment:
```bash
$ cd path/to/project
$ docker-compose build
$ docker-compose up -d
```
