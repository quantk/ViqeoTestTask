<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Routing\Router;

/** @var Router $router */
$router = app()->make(Router::class);
$router->get('/', function () {
    return 'hello';
});
$router->group([
    'middleware' => 'api',
    'prefix' => 'image'
], function (Router $router) {
    $router->post('/', 'ImageController@upload');
    $router->get('/{id}', 'ImageController@getImage');
});