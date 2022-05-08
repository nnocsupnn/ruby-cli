# Framework

Command `make`
- `php make controller ControllerTest:index`
- `php make model ModelTest:table`


Routes
`src/routes`

```php
<?php

/**
 * Register all routes and methods here.
 * keyword => Controller@method
 * 
 * php ruby sample=index,other=test
 */
$routes = [
    "sample" => "ExampleController@index",
    "other" => "SomeController@test"
]; 
```
Running the commands

```javascript
php ruby --routeName methodName
```

