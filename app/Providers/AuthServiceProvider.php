<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        
        // Auth::viaRequest('user', function($request){
        //      return $request->user('sanctum');
        //   //return Sanctum::authenticate($request);
        // });
        
        // // Auth::provider('users', function ($app, array $config) {
        // //     return new UserProvider($app['hash'], $config['model']);
        // // });
        // Auth::provider('eloquent', function (Application $app, array $config) {
        //     // Return an instance of Illuminate\Contracts\Auth\UserProvider...
 
        //     return new EloquentUserProvider($app->make('db')->connection(), $config['model']);
        // });
        





       // Sanctum::ignoreMigrations();
    }
}
