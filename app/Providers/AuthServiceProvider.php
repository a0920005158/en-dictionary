<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Guards\apiGuard;
use App\Models\User;
use App\Models\Post;
use App\Policies\SearchPolicy;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'App\Models\User' => 'App\Policies\searchPolicy',
        User::class => SearchPolicy::class,
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Auth::extend('api-guard', function ($Application) {
            return new apiGuard($Application->request);//User::where('token', $request->token)->first();
        });
    }
}
