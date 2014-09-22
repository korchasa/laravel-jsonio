Jsonio
============
Install:

1. composer.json
```
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:korchasa/laravel-jsonio.git"
        }
    ],
    "require": {
        "korchasa/jsonio": "dev-develop"
    },
    "minimum-stability": "dev"
```

2. config/app.php
```
    'providers' => array(
        'Jsonio\JsonioServicesProvider',
    ),
```
