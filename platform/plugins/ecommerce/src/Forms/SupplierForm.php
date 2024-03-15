<?php

namespace Botble\Ecommerce\Forms;

use BaseHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Enums\CustomerStatusEnum;
use Botble\Ecommerce\Http\Requests\CustomerCreateRequest;
use Botble\Ecommerce\Models\Customer;
use EcommerceHelper;

class SupplierForm extends FormAbstract
{

    /**
     * @var string
     */
    protected $template = 'core/base::forms.form-tabs';

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Customer)
            ->setValidatorClass(CustomerCreateRequest::class)
            ->withCustomFields()
            ->add('firstname', 'text', [
                'label'      => "First name",
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => "First name",
                    'data-counter' => 120,
                ],
            ])
            ->add('lastname', 'text', [
                'label'      => "Last name",
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => "Last name",
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'text', [
                'label'      => trans('plugins/ecommerce::customer.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::customer.email_placeholder'),
                    'data-counter' => 60,
                ],
            ])
            ->add('phone', 'text', [
                'label'      => trans('plugins/ecommerce::customer.phone'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::customer.phone_placeholder'),
                    'data-counter' => 20,
                ],
            ])
            ->add('company_name', 'text', [
                'label'      => 'Company Name',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Company Name',
                    'data-counter' => 100,
                ],
            ])
            ->add('website', 'url', [
                'label'      => "Website",
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => "Website",
                    'data-counter' => 250,
                ],
            ])
            ->add('address', 'text', [
                'label'      => trans('plugins/ecommerce::order.address'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('plugins/ecommerce::order.address'),
                    'data-counter' => 200,
                ],
            ])
            ->add('notes', 'text', [
                'label'      => 'notes',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => 'notes',
                    'data-counter' => 200,
                ],
            ])
        
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => CustomerStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
