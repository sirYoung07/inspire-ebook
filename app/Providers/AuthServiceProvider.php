<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        $this->registerPolicies();

        
        // Auth::viaRequest('session', function (Request $request) {

        //     // return DB::table('personal_access_tokens')
        //     // ->join('users', 'personal_access_tokens.tokenable_id', '=', 'users.id')
        //     // ->where('personal_access_tokens.token', $request->token)
        //     // ->select('users.*')
        //     // ->first();

        // });

    }

    
}
