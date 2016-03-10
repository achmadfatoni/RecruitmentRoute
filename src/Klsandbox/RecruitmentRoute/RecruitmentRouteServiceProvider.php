<?php namespace Klsandbox\RecruitmentRoute;

use Illuminate\Support\ServiceProvider;

class RecruitmentRouteServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

	public function boot() {
		if (!$this->app->routesAreCached()) {
			require __DIR__ . '/../../../routes/routes.php';
		}

		$this->loadViewsFrom(__DIR__ . '/../../../views/', 'recruitment-route');

		$this->publishes([
			__DIR__ . '/../../../views/' => base_path('resources/views/vendor/recruitment-route')
		], 'views');

		$this->publishes([
			__DIR__ . '/../../../database/migrations/' => database_path('/migrations')
		], 'migrations');
	}
}
