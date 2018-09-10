<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserAdmin;
use App\Observers\UserAdminObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    User::observe(UserObserver::class);
    UserAdmin::observe(UserAdminObserver::class);
    Relation::morphMap([
      'user' => 'App\Models\User',
      'user_admin' => 'App\Models\UserAdmin',
    ]);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
      //
  }
}
