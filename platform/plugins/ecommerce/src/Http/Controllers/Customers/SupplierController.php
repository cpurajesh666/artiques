<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Assets;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\CustomerForm;
use Botble\Ecommerce\Forms\SupplierForm;
use Botble\Ecommerce\Http\Requests\AddCustomerWhenCreateOrderRequest;
use Botble\Ecommerce\Http\Requests\CustomerCreateRequest;
use Botble\Ecommerce\Http\Requests\CustomerEditRequest;
use Botble\Ecommerce\Http\Requests\CustomerUpdateEmailRequest;
use Botble\Ecommerce\Http\Requests\SupplierCreateRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\SupplierInterface;
use Botble\Ecommerce\Tables\CustomerTable;
use Botble\Ecommerce\Tables\SupplierTable;
use Botble\Newsletter\Models\Newsletter;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SupplierController extends BaseController
{

    /**
     * @var SupplierInterface
     */
    protected $supplierRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @param SupplierInterface $supplierRepository
     */
    public function __construct(SupplierInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @param SupplierTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(SupplierTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::supplier.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::supplier.create'));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/supplier.js');

        return $formBuilder->create(SupplierForm::class)->remove('is_change_password')->renderForm();
    }

    /**
     * @param CustomerCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(SupplierCreateRequest $request, BaseHttpResponse $response)
    {
        $customer = $this->supplierRepository->getModel();
        $customer->fill($request->input());
        $customer->name = $request->input('firstname') . " " . $request->input('lastname');
        $customer = $this->supplierRepository->createOrUpdate($customer);

        event(new CreatedContentEvent(SUPPLIER_MODULE_SCREEN_NAME, $request, $customer));

        return $response
            ->setPreviousUrl(route('suppliers.index'))
            ->setNextUrl(route('suppliers.edit', $customer->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/supplier.js');

        $supplier = $this->supplierRepository->findOrFail($id);
        
        page_title()->setTitle(trans('plugins/ecommerce::supplier.edit', ['name' => $supplier->name]));

        return $formBuilder->create(SupplierForm::class, ['model' => $supplier])->renderForm();
    }

    /**
     * @param int $id
     * @param CustomerEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, CustomerEditRequest $request, BaseHttpResponse $response)
    {
        $supplier = $this->supplierRepository->findOrFail($id);

        $supplier->fill($request->input());
        $supplier->name = $request->input('firstname') . " " . $request->input('lastname');
        
    
        $supplier = $this->supplierRepository->createOrUpdate($supplier);
        event(new UpdatedContentEvent(SUPPLIER_MODULE_SCREEN_NAME, $request, $supplier));

        return $response
            ->setPreviousUrl(route('suppliers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $supplier = $this->supplierRepository->findOrFail($id);
            $this->supplierRepository->delete($supplier);
            event(new DeletedContentEvent(SUPPLIER_MODULE_SCREEN_NAME, $request, $supplier));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $supplier = $this->supplierRepository->findOrFail($id);
            $this->supplierRepository->delete($supplier);
            event(new DeletedContentEvent(SUPPLIER_MODULE_SCREEN_NAME, $request, $supplier));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getListSupplierForSelect(BaseHttpResponse $response)
    {
        $suppliers = $this->supplierRepository
            ->allBy([], [], ['id', 'name'])
            ->toArray();

        return $response->setData($suppliers);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getListSupplierForSearch(Request $request, BaseHttpResponse $response)
    {
        $suppliers = $this->supplierRepository
            ->getModel()
            ->where('name', 'LIKE', '%' . $request->input('keyword') . '%')
            ->simplePaginate(5);

        return $response->setData($suppliers);
    }

    // /**
    //  * @param int $id
    //  * @param CustomerUpdateEmailRequest $request
    //  * @param BaseHttpResponse $response
    //  * @return BaseHttpResponse
    //  */
    // public function postUpdateEmail($id, CustomerUpdateEmailRequest $request, BaseHttpResponse $response)
    // {
    //     $this->supplierRepository->createOrUpdate(['email' => $request->input('email')], ['id' => $id]);

    //     return $response->setMessage(trans('core/base::notices.update_success_message'));
    // }

    // /**
    //  * @param int $id
    //  * @param BaseHttpResponse $response
    //  * @return BaseHttpResponse
    //  */
    // public function getCustomerOrderNumbers($id, BaseHttpResponse $response)
    // {
    //     $customer = $this->supplierRepository->findById($id);
    //     if (!$customer) {
    //         return $response->setData(0);
    //     }

    //     return $response->setData($customer->orders()->count());
    // }

    // /**
    //  * @param AddCustomerWhenCreateOrderRequest $request
    //  * @param BaseHttpResponse $response
    //  * @return BaseHttpResponse
    //  */
    // public function postCreateCustomerWhenCreatingOrder(
    //     AddCustomerWhenCreateOrderRequest $request,
    //     BaseHttpResponse $response
    // ) {
    //     $request->merge(['password' => bcrypt(time())]);
    //     $customer = $this->supplierRepository->createOrUpdate($request->input());
    //     $customer->avatar = (string)$customer->avatar_url;

    //     event(new CreatedContentEvent(SUPPLIER_MODULE_SCREEN_NAME, $request, $customer));

    //     $request->merge([
    //         'customer_id' => $customer->id,
    //         'is_default'  => true,
    //     ]);

    //     $address = $this->addressRepository->createOrUpdate($request->input());

    //     return $response
    //         ->setData(compact('address', 'customer'))
    //         ->setMessage(trans('core/base::notices.create_success_message'));
    // }
}
