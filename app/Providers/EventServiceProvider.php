<?php

namespace App\Providers;

use App\Events\Factors\FactorCreated;
use App\Listeners\Factors\FcatorCreatedHandler;
use App\Events\Factors\FactorDeleted;
use App\Listeners\Factors\FcatorDeletedHandler;
use App\Events\Factors\FactorUpdated;
use App\Events\WalletAmountChanged;
use App\Listeners\Factors\FcatorUpdatedHandler;

use App\Events\ProductChange\ProductChangeCreated;
use App\Events\ProductChange\ProductChangeDeleted;
use App\Events\ProductChange\ProductChangeUpdated;
use App\Events\ProductChange\ReturnProductChangeCreted;
use App\Listeners\Factors\InputFactor\CreateWalletChangeLog;
use App\Listeners\Factors\InputFactor\UpdateWalletAmount;
use App\Listeners\WalletChangeHandler;
use App\Listeners\LogHandler;
use App\Listeners\ProductChange\ProductChangeCreatedHandler;
use App\Listeners\ProductChange\ProductChangeDeletedHandler;
use App\Listeners\ProductChange\ProductChangeUpdatedHandler;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // productchange

        ProductChangeCreated::class => [
            ProductChangeCreatedHandler::class,
            LogHandler::class,
        ],
        ProductChangeUpdated::class => [
            ProductChangeUpdatedHandler::class,
            LogHandler::class,
        ],
        ProductChangeDeleted::class => [
            ProductChangeDeletedHandler::class,
            LogHandler::class,
        ],

        //factor events

        FactorCreated::class => [
            FcatorCreatedHandler::class,
            LogHandler::class,
        ],
        FactorUpdated::class => [
            FcatorUpdatedHandler::class,
            LogHandler::class,
        ],
        FactorDeleted::class => [
            FcatorDeletedHandler::class,
            LogHandler::class,
        ],

        WalletAmountChanged::class => [
            WalletChangeHandler::class,
        ],

        ReturnProductChangeCreated::class => [
            ReturnProductChangeHandler::class,
        ],

     

      

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
