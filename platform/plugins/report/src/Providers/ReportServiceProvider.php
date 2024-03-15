<?php

namespace Botble\Report\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
    }

    public function boot()
    {
        $this
            ->setNamespace('plugins/report')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadRoutes(['web'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-report',
                'priority'    => 3,
                'parent_id'   => null,
                'name'        => 'Report',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.inventory'),
                'permissions' => ['reports.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-report-product',
                'priority'    => 123,
                'parent_id'   => 'cms-plugins-report',
                'name'        => 'Inventory Report',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.inventory'),
                'permissions' => ['reports.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-report-product-sold',
                'priority'    => 124,
                'parent_id'   => 'cms-plugins-report',
                'name'        => 'Products Sold Report',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.products.sold'),
                'permissions' => ['reports.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-report-customers-enquiry-list',
                'priority'    => 125,
                'parent_id'   => 'cms-plugins-report',
                'name'        => 'Guest Customers',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.guest.users'),
                'permissions' => ['reports.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-report-most-viewed',
                'priority'    => 126,
                'parent_id'   => 'cms-plugins-report',
                'name'        => 'Most Viewed Report',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.mostViewed'),
                'permissions' => ['reports.index'],
            ])->registerItem([
                'id'          => 'cms-plugins-report-top-selling',
                'priority'    => 126,
                'parent_id'   => 'cms-plugins-report',
                'name'        => 'Top Selling Report',
                'icon'        => 'far fa-chart-bar',
                'url'         => route('reports.topSelling'),
                'permissions' => ['reports.index'],
            ]);
        });
    }
}
