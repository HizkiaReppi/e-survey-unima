<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Student;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\StudentObserver;
use App\Observers\SurveyResponseObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        Student::observe(StudentObserver::class);
        User::observe(UserObserver::class);
        SurveyResponse::observe(SurveyResponseObserver::class);

        Gate::define('super-admin', function (User $user) {
            return $user->role === 'super-admin'
                ? Response::allow()
                : Response::deny('You must be an super administrator.');
        });

        Gate::define('admin', function (User $user) {
            return $user->role === 'admin'
                ? Response::allow()
                : Response::deny('You must be an administrator.');
        });

        Gate::define('quality-assurance', function (User $user) {
            return $user->role === 'quality-assurance'
                ? Response::allow()
                : Response::deny('You must be an quality-assurance.');
        });

        Gate::define('student', function (User $user) {
            return $user->role === 'student'
                ? Response::allow()
                : Response::deny('You must be an student.');
        });
    }
}
