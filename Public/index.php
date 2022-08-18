<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

namespace Expo\Pub;

use Expo\Routes\PageRouter;

const __ROOT__ = __DIR__ . '/..';

require __ROOT__ . '/Config/debugFlag.php';
require __ROOT__ . '/Config/errorReporting.php';
require __ROOT__ . '/Routes/autoloader.php';
require __ROOT__ . '/Routes/routeTable.php';
require __ROOT__ . '/Routes/apiRouteTable.php';

PageRouter::callback($_SERVER['REQUEST_URI']);
