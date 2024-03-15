<?php

namespace Botble\Promotions\Http\Controllers;

use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Promotions\Forms\PromotionForm;
use Botble\Promotions\Http\Requests\PromotionRequest;
use Botble\Promotions\Repositories\Interfaces\PromotionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Promotions\Models\PromotionSchedule;
use Illuminate\Http\Request;
use Exception;
use Botble\Promotions\Tables\PromotionTable;
use PDO;

class PromotionController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var PromotionInterface
     */
    protected $promotionRepository;

    /**
     * SimpleSliderController constructor.
     * @param PromotionInterface $promotionRepository
     * @param SimpleSliderItemInterface $simpleSliderItemRepository
     */
    public function __construct(
        PromotionInterface $promotionRepository
    )
    {
        $this->promotionRepository = $promotionRepository;
    }

    /**
     * @param PromotionTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(PromotionTable $dataTable)
    {
        page_title()->setTitle('Promotions');

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle('Promotions');

        Assets::addScripts([
            'jquery-ui',
            'input-mask',
            'blockui',
        ])->addScriptsDirectly([
                'vendor/core/plugins/promotions/js/promotion.js',
            ]);

        return $formBuilder
            ->create(PromotionForm::class)
            ->setUseInlineJs(true)
            ->renderForm();
    }

    /**
     * @param PromotionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(PromotionRequest $request, BaseHttpResponse $response)
    {
        
        $data = $request->only(['_token', 'name', 'text', 'type']);

        if($request->input('type') == "permanant"){
            $data['from'] = $request->input('from');
            $data['never_expires'] = $request->input('never_expires', 0);
            
            if($data['never_expires'] != 1 ){
                $data['to'] = $request->input('to');
            }
        }


        $promotion = $this->promotionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent('promotion', $request, $promotion));

        return $response
            ->setPreviousUrl(route('promotions.index'))
            ->setNextUrl(route('promotions.edit', $promotion->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $promotion = $this->promotionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $promotion));

        Assets::addScripts([
            'moment',
            'datetimepicker',
            'jquery-ui',
            'input-mask',
            'blockui',
        ])->addScriptsDirectly([
            'vendor/core/plugins/promotions/js/promotion.js',
        ]);

        page_title()->setTitle('Edit Promotion' . ' "' . $promotion->name . '"');

        return $formBuilder
            ->create(PromotionForm::class, ['model' => $promotion])
            ->setUseInlineJs(true)
            ->renderForm();
    }

    /**
     * @param $id
     * @param PromotionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, PromotionRequest $request, BaseHttpResponse $response)
    {
        $promotion = $this->promotionRepository->findOrFail($id);
        
        $oldType = $promotion->type;
        $newType = $request->input('type');

        $data = $request->only(['_token', 'name', 'text', 'type']);

        if($request->input('type') == "permanant"){
            $data['from'] = $request->input('from');
            $data['never_expires'] = $request->input('never_expires', 0);
            
            if($data['never_expires'] != 1 ){
                $data['to'] = $request->input('to');
            }
        }

        $promotion->fill($data);

        $this->promotionRepository->createOrUpdate($promotion);

        if($oldType != $newType){
            PromotionSchedule::truncate();
        }

        event(new UpdatedContentEvent('promotion', $request, $promotion));

        return $response
            ->setPreviousUrl(route('promotions.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return array|BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $promotion = $this->promotionRepository->findOrFail($id);
            $this->promotionRepository->delete($promotion);

            event(new DeletedContentEvent('promotion', $request, $promotion));

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
     * @return array|BaseHttpResponse|\Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->promotionRepository, 'promotion');
    }
}
