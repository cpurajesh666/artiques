<?php

use Botble\Promotions\Models\Promotion;
use Botble\Promotions\Models\PromotionSchedule;
use Carbon\Carbon;

if (!function_exists('get_today_promotion')) {
    /**
     * @return mixed
     */
    function get_today_promotion()
    {
        $today = Carbon::now()->format('d-m-y');
        
        if(!PromotionSchedule::where('date', $today)->exists()){
            generate_promotion_schedules();
        } 

        $promotions = [];

        $todaySchedule = PromotionSchedule::with('promotion')->where('date', $today)->first();

        if($todaySchedule && $todaySchedule->promotion){
            $promotions[] = [
                'title' => $todaySchedule->promotion->name,
                'text' => $todaySchedule->promotion->text
            ];
        }

        $permanantPromotionsWithFromAndTo = Promotion::where('status', 'published')->where('type', 'permanant')
        ->where('from', '<=', now()->format('Y-m-d'))->where('to', '>=', now()->format('Y-m-d'))->where('never_expires', 0)->get();

        foreach($permanantPromotionsWithFromAndTo as $promotion){
            $promotions[] = [
                'title' => $promotion->name,
                'text' => $promotion->text
            ];
        }

        $permanantPromotionsNoExpiry = Promotion::where('status', 'published')->where('type', 'permanant')
        ->where('from', '<=', now()->format('Y-m-d'))->where('never_expires', 1)->get(); 

        
        foreach($permanantPromotionsNoExpiry as $promotion){
            $promotions[] = [
                'title' => $promotion->name,
                'text' => $promotion->text
            ];
        }
        
        return $promotions;
    }
}

if (!function_exists('generate_promotion_schedules')) {
    /**
     * @return mixed
     */
    function generate_promotion_schedules()
    {
        $promotionIds = Promotion::where('status', 'published')->where('type', 'daily')->pluck('id')->shuffle();

        $promotionSchedules = [];

        $dayIncrement = 0;
        foreach($promotionIds as $promotionId){
            $promotionSchedules[] = [
                'date' => Carbon::now()->addDay($dayIncrement)->format('d-m-y'),
                'promotion_id' => $promotionId,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $dayIncrement++;
        }
        PromotionSchedule::insert($promotionSchedules);
    }

}