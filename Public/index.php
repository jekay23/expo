<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

namespace Expo\Pub;

use Expo\Routes\Router;

require __DIR__ . '/../Config/errorReporting.php';

require __DIR__ . '/../Routes/autoloader.php';

require __DIR__ . '/../Routes/routeTable.php';

Router::execute($_SERVER['REQUEST_URI']);
