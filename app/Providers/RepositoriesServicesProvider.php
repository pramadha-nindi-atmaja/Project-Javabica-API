<?php

namespace App\Providers;

use App\Interfaces\BlogInterface;
use App\Interfaces\LocationStoreInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\OrderProductInterface;
use App\Interfaces\PermissionInterface;
use App\Interfaces\ProductCategoryInterface;
use App\Interfaces\ProductCollectionInterface;
use App\Interfaces\ProductImageInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ProductPriceInterface;
use App\Interfaces\ProductVariantInterface;
use App\Interfaces\RolesInterface;
use App\Interfaces\ShippingAddressInterface;
use App\Interfaces\TaxonomyInterface;
use App\Interfaces\UsersInterface;
use App\Interfaces\BlogCategoryInterface;
use App\Interfaces\MachineInterface;
use App\Interfaces\VoucherInterface;
use App\Interfaces\HistoryVoucherInterface;
use App\Repositories\BlogRepository;
use App\Repositories\LocationStoreRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductCollectionRepository;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductPriceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\RolesRepository;
use App\Repositories\ShippingAddressRepository;
use App\Repositories\TaxonomyRepository;
use App\Repositories\UsersRepository;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\MachineRepository;
use App\Repositories\VoucherRepository;
use App\Repositories\HistoryVoucherRepository;
use Illuminate\Support\ServiceProvider;


class RepositoriesServicesProvider extends ServiceProvider
{
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
    
        $this->app->bind(UsersInterface::class,UsersRepository::class);
        $this->app->bind(TaxonomyInterface::class,TaxonomyRepository::class);
        $this->app->bind(RolesInterface::class,RolesRepository::class);
        $this->app->bind(PermissionInterface::class,PermissionRepository::class);
        $this->app->bind(ProductInterface::class,ProductRepository::class);
        $this->app->bind(ProductCollectionInterface::class,ProductCollectionRepository::class);
        $this->app->bind(ProductImageInterface::class,ProductImageRepository::class);
        $this->app->bind(ProductCategoryInterface::class,ProductCategoryRepository::class);
        $this->app->bind(ProductVariantInterface::class,ProductVariantRepository::class);
        $this->app->bind(ProductPriceInterface::class,ProductPriceRepository::class);
        $this->app->bind(OrderInterface::class,OrderRepository::class);
        $this->app->bind(OrderProductInterface::class,OrderProductRepository::class);
        $this->app->bind(ShippingAddressInterface::class,ShippingAddressRepository::class);
        $this->app->bind(LocationStoreInterface::class,LocationStoreRepository::class);
        $this->app->bind(BlogInterface::class,BlogRepository::class);
        $this->app->bind(BlogCategoryInterface::class, BlogCategoryRepository::class);
        $this->app->bind(MachineInterface::class, MachineRepository::class);
        $this->app->bind(VoucherInterface::class, VoucherRepository::class);
        $this->app->bind(HistoryVoucherInterface::class, HistoryVoucherRepository::class);
    }
}
