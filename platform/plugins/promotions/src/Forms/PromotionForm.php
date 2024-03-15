<?php

namespace Botble\Promotions\Forms;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Promotions\Http\Requests\PromotionRequest;
use Botble\Promotions\Models\Promotion;
use Botble\Table\TableBuilder;

class PromotionForm extends FormAbstract
{

    /**
     * @var TableBuilder
     */
    protected $tableBuilder;

    /**
     * SimpleSliderForm constructor.
     * @param TableBuilder $tableBuilder
     */
    public function __construct(TableBuilder $tableBuilder)
    {
        parent::__construct();
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Promotion)
            ->setValidatorClass(PromotionRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 120,
                ],
            ])
            ->add('text', 'text', [
                'label'      => 'Description',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 200,
                ],
            ])
            ->add('type', 'customSelect', [
                'label'      => "Type",
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => ['daily' => 'daily', 'permanant' => 'permanant'],
            ])
            ->add('from', 'html', [
                'label'      => "From date",
                'label_attr' => ['class' => 'control-label'],
                'html' => $this->getFromField($this->getModel() ? $this->getModel()->from : ''),

            ])
            ->add('never_expires', 'checkbox', [
                'label'      => "Never expires",
                'label_attr' => ['class' => 'control-label'],
            ]);
            // ->add('image', 'mediaImage', [
            //     'label'      => trans('core/base::forms.image'),
            //     'label_attr' => ['class' => 'control-label required'],
            // ])

           
            $this->add('to', 'html', [
                'label'      => "To date",
                'label_attr' => ['class' => 'control-label required'],
                'html' => $this->getToField($this->getModel() ? $this->getModel()->to : ''),
            ]);
        
            $this->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }

    public function getFromField($from){
        return "<div class='form-group mb-3'><label for='type' class='control-label'>From date</label><input type='date' class='form-control' id='from' name='from' value='{$from}'></div>";
    }
    public function getToField($to){
        $class = "control-label to-label " . ($this->getModel() && $this->getModel()->never_expires == 1 ? 'hidden' : '');
        return("<div class='form-group mb-3'><label for='type' class='{$class}'>To date</label><input type='date' class='form-control' id='to' name='to' value='{$to}'></div>");
    }
}


