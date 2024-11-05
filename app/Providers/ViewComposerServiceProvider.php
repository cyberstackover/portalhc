<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\GeneralModel;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use DB;

class ViewComposerServiceProvider extends ServiceProvider
{
    protected $gm;

    public function __construct()
    {
        $this->gm = new GeneralModel();
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerLayout();
    }

    protected function registerLayout()
    {
        View::composer('layouts.partials._aside._menu', function ($view) {
            $view->with('assidemenu', $this->gm->getassidemenu());
        });
    }
    
}
