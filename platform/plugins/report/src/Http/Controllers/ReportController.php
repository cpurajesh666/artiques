<?php

namespace Botble\Report\Http\Controllers;

use App\Models\User;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Models\Category;
use Botble\Ecommerce\Facades\ProductCategoryHelperFacade;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Supplier;
use Botble\Report\Tables\GuestUsersTable;
use Botble\Report\Tables\MostViewedCategoryTable;
use Botble\Report\Tables\MostViewedProductTable;
use Botble\Report\Tables\MostViewedSubCategoryTable;
use Botble\Report\Tables\MostViewedSubSubCategoryTable;
use Botble\Report\Tables\TopSellingCategoryTable;
use Botble\Report\Tables\TopSellingProductTable;
use Botble\Report\Tables\TopSellingSubCategoryTable;
use Botble\Report\Tables\TopSellingSubSubCategoryTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Assets;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Report\Tables\MostSearchedTable;
use Botble\Report\Tables\TopSellingSupplierTable;


class ReportController extends BaseController
{
    public function inventory()
    {
        page_title()->setTitle("Inventory Report");

        Assets::removeStyles([
                'select2'
            ])
            ->removeScripts([
                'select2',
            ]);

        $categories = ProductCategoryHelperFacade::getProductCategoriesWithIndent('&nbsp;&nbsp;&nbsp;&nbsp;');
        return view(
            'plugins/report::inventory',
            ['employees' => User::where('users.email', '!=', 'admin@layorz.com')->get(), 'categories' => $categories]
        );
    }


    public function productsSold(){
        page_title()->setTitle("Products Sold Report");

        $categories = ProductCategoryHelperFacade::getProductCategoriesWithIndent('&nbsp;&nbsp;&nbsp;&nbsp;');
            return view('plugins/report::products-sold', 
             ['suppliers' => Supplier::all(), 'categories' => $categories]);

    }

    /**
     * @param GuestUsersTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function guestUsers(GuestUsersTable $dataTable)
    {
        page_title()->setTitle("Guest Customers");
        
        return $dataTable->renderTable();
    }

        /**
     * @param MostViewedCategoryTable $recentOrdersTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function mostViewedCategoryData(MostViewedCategoryTable $mostViewedCategoryTable)
    {
        return $mostViewedCategoryTable->renderTable();
    }

    /**
     * @param MostViewedSubCategoryTable $mostViewedSubCategoryTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function mostViewedSubCategoryData(MostViewedSubCategoryTable $mostViewedSubCategoryTable)
    {
        return $mostViewedSubCategoryTable->renderTable();
    }

    /**
     * @param MostViewedSubSubCategoryTable $mostViewedSubCategoryTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function mostViewedSubSubCategoryData(MostViewedSubSubCategoryTable $mostViewedSubSubCategoryTable)
    {
        return $mostViewedSubSubCategoryTable->renderTable();
    }

    /**
     * @param MostViewedProductTable $mostViewedProductTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function mostViewedProductData(MostViewedProductTable $mostViewedProductTable)
    {
        return $mostViewedProductTable->renderTable();
    }

    /**
     * @param MostSearchedTable $mostSearchedTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function mostSearchedData(MostSearchedTable $mostSearchedTable)
    {
        return $mostSearchedTable->renderTable();
    }


    public function mostViewed(
        MostViewedCategoryTable $mostViewedCategoryTable, 
        MostViewedSubCategoryTable $mostViewedSubCategoryTable,
        MostViewedSubSubCategoryTable $mostViewedSubSubCategoryTable,
        MostViewedProductTable $mostViewedProductTable,
        MostSearchedTable $mostSearchedTable
     ){
        page_title()->setTitle("Most Viewed Report");

        return view('plugins/report::most-viewed', [
            'categoryTable' => $mostViewedCategoryTable, 
            'subCategoryTable' => $mostViewedSubCategoryTable, 
            'subSubCategoryTable' => $mostViewedSubSubCategoryTable, 
            'productTable' => $mostViewedProductTable,
            'searchTable' => $mostSearchedTable
        ]);
    
    }


   /**
     * @param TopSellingCategoryTable $topSellingCategoryTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function TopSellingCategoryData(TopSellingCategoryTable $topSellingCategoryTable)
    {
        return $topSellingCategoryTable->renderTable();
    }

    /**
     * @param TopSellingSubCategoryTable $topSellingSubCategoryTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function TopSellingSubCategoryData(TopSellingSubCategoryTable $topSellingSubCategoryTable)
    {
        return $topSellingSubCategoryTable->renderTable();
    }

    /**
     * @param TopSellingSubSubCategoryTable $topSellingSubSubCategoryTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function TopSellingSubSubCategoryData(TopSellingSubSubCategoryTable $topSellingSubSubCategoryTable)
    {
        return $topSellingSubSubCategoryTable->renderTable();
    }

    /**
     * @param TopSellingProductTable $topSellingProductTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function TopSellingProductData(TopSellingProductTable $topSellingProductTable)
    {
        return $topSellingProductTable->renderTable();
    }
    
    /**
     * @param TopSellingSupplierTable $topSellingSupplierTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function TopSellingSupplierData(TopSellingSupplierTable $topSellingSupplierTable)
    {
        return $topSellingSupplierTable->renderTable();
    }


    public function topSelling(
        TopSellingCategoryTable $topSellingCategoryTable, 
        TopSellingSubCategoryTable $topSellingSubCategoryTable,
        TopSellingSubSubCategoryTable $topSellingSubSubCategoryTable,
        TopSellingProductTable $topSellingProductTable,
        TopSellingSupplierTable $topSellingSupplierTable,
        Request $request
    ){
        page_title()->setTitle("Top Selling");

        return view('plugins/report::top-selling', [
            'categoryTable' => $topSellingCategoryTable, 
            'subCategoryTable' => $topSellingSubCategoryTable, 
            'subSubCategoryTable' => $topSellingSubSubCategoryTable, 
            'productTable' => $topSellingProductTable,
            'supplierTable' => $topSellingSupplierTable,
            'fromDate' => $request->input('fromDate'),
            'toDate' => $request->input('toDate'),
        ]);
    }

    public function inventoryReport(Request $request)
    {
        $productQuery = DB::table('ec_products as p')->join('ec_product_category_product as pcp', 'pcp.product_id', 'p.id');

        if(count($request->input('categories', [])) > 0){

            $categories = ProductCategory::whereIn('id', $request->input('categories'))->get();

            $categoryIds = [];
            foreach($categories as $category){
                $categoryIds = array_merge($categoryIds, $category->getChildrenIds($category, [$category->id]));
            }

            if(count($categoryIds) > 0){
                $productQuery->whereIn('pcp.category_id', array_unique($categoryIds));
            }
        }

        if($request->input("fromDate")) {
            $productQuery = $productQuery->whereDate('p.created_at','>=', Carbon::parse($request->input("fromDate"))->format('Y-m-d'));
        }

        if($request->input("toDate")) {
            $productQuery = $productQuery->whereDate('p.created_at','<=', Carbon::parse($request->input("toDate"))->format('Y-m-d'));
        }

        if($request->input("employeeId")) {
            $productQuery = $productQuery->where('p.user_id', $request->input("employeeId"));
        }

        $productQuery->groupBy('p.id');

        $estimatedValue = $productQuery->select('p.id', 'p.cost_price', 'p.quantity', 'p.price')->get();
        
        $estimatedValue->map(function($value){
            $value->cost_value = $value->cost_price * $value->quantity;
            $value->selling_value = $value->price * $value->quantity;
        });

        return response()->json([
                'count' => $productQuery->pluck('p.id')->count(),
                'cost_value' => round($estimatedValue->sum('cost_value')),
                'selling_value' => round($estimatedValue->sum('selling_value'))
            ]);;

    }

    public function productsSoldReport(Request $request)
    {

        $productQuery = DB::table('ec_order_product as op')
        ->join('ec_product_category_product as pcp', 'pcp.product_id', 'op.product_id')
        ->join('ec_products as p', 'p.id', 'op.product_id')
        ->join('ec_orders as o', 'o.id', 'op.order_id')
        ->where('o.status', 'completed');

        if(count($request->input('categories', [])) > 0){

            $categories = ProductCategory::whereIn('id', $request->input('categories'))->get();

            $categoryIds = [];
            foreach($categories as $category){
                $categoryIds = array_merge($categoryIds, $category->getChildrenIds($category, [$category->id]));
            }

            if(count($categoryIds) > 0){
                $productQuery->whereIn('pcp.category_id', array_unique($categoryIds));
            }
        }


        if($request->input("fromDate")) {
            $productQuery = $productQuery->whereDate('op.created_at','>=', Carbon::parse($request->input("fromDate"))->format('Y-m-d'));
        }

        if($request->input("toDate")) {
            $productQuery = $productQuery->whereDate('op.created_at','<=', Carbon::parse($request->input("toDate"))->format('Y-m-d'));
        }

        if($request->input("supplier")) {
            $productQuery = $productQuery->where('p.supplier_id', $request->input("supplier"));
        }

        $productQuery->groupBy('op.order_id', 'op.product_id');

        return response()->json([
            'count' => $productQuery->pluck('op.qty')->sum(),
        ]);;


    }
}
