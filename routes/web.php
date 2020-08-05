<?php
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'middleware' => ['api.throttle'],
    'limit' => 100,
    'expires' => 5,
    'prefix' => 'api/v1',
    'namespace' => 'App\Http\Controllers\V1',
], function ($api) {
    // This section implement a custom middleware
    /*$api->post('register', 'UserController@register');
    $api->post('login', 'UserController@authenticate');
    $api->get('open', 'DataController@open');

    $api->group(['middleware' => 'jwt.verify'], function ($api) {
        $api->get('user', 'UserController@getAuthenticatedUser');
        $api->get('closed', 'DataController@closed');
    });*/

    //This section implement Dingo Auth middleware
    $api->group(['middleware' => 'api.auth'], function ($api) {
        //Posts protected routes
        $api->resource('posts', "PostController", [
            'except' => ['show', 'index']
        ]);

        //Comments protected routes
        /*$api->resource('comments', "CommentController", [
            'except' => ['show', 'index']
        ]);
        $api->post('posts/{id}/comments', 'CommentController@store');*/

        // User info
        $api->post('me', [
                'uses' => 'Auth\AuthController@me',
                'as' => 'api.Auth.me'
            ]
        );

        // Logout user by removing token
        $api->delete('/', [
                'uses' => 'Auth\AuthController@logout',
                'as' => 'api.Auth.logout'
            ]
        );
        // Refresh token
        $api->patch('/', [
                'uses' => 'Auth\AuthController@refresh',
                'as' => 'api.Auth.refresh'
            ]
        );

    });

    /*$api->get('posts', 'PostController@index');
    $api->get('posts/{id}', 'PostController@show');
    $api->get('posts/{id}/comments', 'CommentController@index');
    $api->get('comments/{id}', 'CommentController@show');*/
    $api->post('/auth/register', [
        'as' => 'api.Auth.register',
        'uses' => 'Auth\AuthController@register'
    ]);
    $api->post('/auth/login', [
            'as' => 'api.Auth.login',
            'uses' => 'Auth\AuthController@login',
        ]
    );
});

$router->get('/', function () use ($router) {
    return "Blog " . $router->app->version();
});
