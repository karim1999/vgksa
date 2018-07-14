<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('users', function (){
    return \App\User::all();
});
Route::get('register', function ($data){
    return App\User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'api_token' => md5($data['email'].$data['name']),
        'password' => bcrypt($data['password']),
    ]);
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::group([

    'middleware' => 'jwt.auth',

], function ($router) {

//Projects api routes
    Route::post('projects', 'API\projectController@store');
    Route::put('projects/{project}', 'API\projectController@update');
    Route::delete('projects/{project}', 'API\projectController@delete');

    //add to favorites
    Route::post('favorites/{project}', function (\App\Project $project){
        $user= \App\User::findOrFail(auth()->user()->id);
        $user->favorites()->attach($project);
        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    });
    //remove from favorites
    Route::delete('favorites/{project}', function (\App\Project $project){
        $user= \App\User::findOrFail(auth()->user()->id);
        $user->favorites()->detach($project);
        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    });

    //invest in project
    Route::post('invest/{project}', function (\App\Project $project, Request $request){
        $user= \App\User::findOrFail(auth()->user()->id);
        if($user->jointProjects->contains($project)){
            $user->jointProjects()->detach($project);
        }
        $user->jointProjects()->attach($project, ["amount" => $request->amount]);
        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    });
    //remove from favorites
    Route::delete('invest/{project}', function (\App\Project $project){
        $user= \App\User::findOrFail(auth()->user()->id);
        $user->jointProjects()->detach($project);
        return response()->json(auth()->user()->load('favorites', 'projects', 'jointprojects'));
    });
});

Route::get('projects', 'API\projectController@index');
Route::get('categories', function(){
    return \App\Category::all();
});
Route::get('projects/paginate', 'API\projectController@paginate');
Route::get('projects/{project}', 'API\projectController@show');
