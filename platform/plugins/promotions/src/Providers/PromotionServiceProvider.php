<?php

namespace Botble\Promotions\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Promotions\Models\Promotion;
use Botble\Promotions\Repositories\Caches\PromotionCacheDecorator;
use Botble\Promotions\Repositories\Eloquent\PromotionRepository;
use Botble\Promotions\Repositories\Interfaces\PromotionInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Language;

class PromotionServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(PromotionInterface::class, function () {
            return new PromotionCacheDecorator(new PromotionRepository(new Promotion));
        });
    }

    public function boot()
    {
        $this
            ->setNamespace('plugins/promotions')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-promotions',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'Promotions',
                'icon'        => 'far fa-image',
                'url'         => route('promotions.index'),
                'permissions' => ['promotions.index'],
            ]);
        });

        $this->app->booted(function () {
        });
    }
}
