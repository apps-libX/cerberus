<?php
/**
 * routes.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 12/01/16 22:22.
 */

/*
|--------------------------------------------------------------------------
| Cerberus Package Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Cerberus\Controllers'], function () {

    // Cerberus Session Routes
    Route::get('login', ['as' => 'cerberus.login', 'uses' => 'SessionController@create']);
    Route::get('logout', ['as' => 'cerberus.logout', 'uses' => 'SessionController@destroy']);
    Route::get('sessions/create', ['as' => 'cerberus.session.create', 'uses' => 'SessionController@create']);
    Route::post('sessions/store', ['as' => 'cerberus.session.store', 'uses' => 'SessionController@store']);
    Route::delete('sessions/destroy', ['as' => 'cerberus.session.destroy', 'uses' => 'SessionController@destroy']);

    // Cerberus Registration
    Route::get('register', ['as' => 'cerberus.register.form', 'uses' => 'RegistrationController@registration']);
    Route::post('register', ['as' => 'cerberus.register.user', 'uses' => 'RegistrationController@register']);
    Route::get('users/activate/{hash}/{code}', ['as' => 'cerberus.activate', 'uses' => 'RegistrationController@activate']);
    Route::get('reactivate', ['as' => 'cerberus.reactivate.form', 'uses' => 'RegistrationController@resendActivationForm']);
    Route::post('reactivate', ['as' => 'cerberus.reactivate.send', 'uses' => 'RegistrationController@resendActivation']);
    Route::get('forgot', ['as' => 'cerberus.forgot.form', 'uses' => 'RegistrationController@forgotPasswordForm']);
    Route::post('forgot', ['as' => 'cerberus.reset.request', 'uses' => 'RegistrationController@sendResetPasswordEmail']);
    Route::get('reset/{hash}/{code}', ['as' => 'cerberus.reset.form', 'uses' => 'RegistrationController@passwordResetForm']);
    Route::post('reset/{hash}/{code}', ['as' => 'cerberus.reset.password', 'uses' => 'RegistrationController@resetPassword']);

    // Cerberus Profile
    Route::get('profile', ['as' => 'cerberus.profile.show', 'uses' => 'ProfileController@show']);
    Route::get('profile/edit', ['as' => 'cerberus.profile.edit', 'uses' => 'ProfileController@edit']);
    Route::put('profile', ['as' => 'cerberus.profile.update', 'uses' => 'ProfileController@update']);
    Route::post('profile/password', ['as' => 'cerberus.profile.password', 'uses' => 'ProfileController@changePassword']);

    // Cerberus Users
    Route::get('users', ['as' => 'cerberus.users.index', 'uses' => 'UserController@index']);
    Route::get('users/create', ['as' => 'cerberus.users.create', 'uses' => 'UserController@create']);
    Route::post('users', ['as' => 'cerberus.users.store', 'uses' => 'UserController@store']);
    Route::get('users/{hash}', ['as' => 'cerberus.users.show', 'uses' => 'UserController@show']);
    Route::get('users/{hash}/edit', ['as' => 'cerberus.users.edit', 'uses' => 'UserController@edit']);
    Route::post('users/{hash}/password', ['as' => 'cerberus.password.change', 'uses' => 'UserController@changePassword']);
    Route::post('users/{hash}/memberships', ['as' => 'cerberus.users.memberships', 'uses' => 'UserController@updateGroupMemberships']);
    Route::put('users/{hash}', ['as' => 'cerberus.users.update', 'uses' => 'UserController@update']);
    Route::delete('users/{hash}', ['as' => 'cerberus.users.destroy', 'uses' => 'UserController@destroy']);
    Route::get('users/{hash}/suspend', ['as' => 'cerberus.users.suspend', 'uses' => 'UserController@suspend']);
    Route::get('users/{hash}/unsuspend', ['as' => 'cerberus.users.unsuspend', 'uses' => 'UserController@unsuspend']);
    Route::get('users/{hash}/ban', ['as' => 'cerberus.users.ban', 'uses' => 'UserController@ban']);
    Route::get('users/{hash}/unban', ['as' => 'cerberus.users.unban', 'uses' => 'UserController@unban']);

    // Cerberus Groups
    Route::get('groups', ['as' => 'cerberus.groups.index', 'uses' => 'GroupController@index']);
    Route::get('groups/create', ['as' => 'cerberus.groups.create', 'uses' => 'GroupController@create']);
    Route::post('groups', ['as' => 'cerberus.groups.store', 'uses' => 'GroupController@store']);
    Route::get('groups/{hash}', ['as' => 'cerberus.groups.show', 'uses' => 'GroupController@show']);
    Route::get('groups/{hash}/edit', ['as' => 'cerberus.groups.edit', 'uses' => 'GroupController@edit']);
    Route::put('groups/{hash}', ['as' => 'cerberus.groups.update', 'uses' => 'GroupController@update']);
    Route::delete('groups/{hash}', ['as' => 'cerberus.groups.destroy', 'uses' => 'GroupController@destroy']);

});
