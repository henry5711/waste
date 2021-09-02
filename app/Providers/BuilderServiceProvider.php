<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 02/08/18
 * Time: 01:33 PM
 */

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blueprint::macro('columns', function () {

            $this->string('name')->nullable();
            $this->string('title')->nullable();
            $this->boolean('deleted')->default(false);
            $this->timestamps()->nullable();

            return $this;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}