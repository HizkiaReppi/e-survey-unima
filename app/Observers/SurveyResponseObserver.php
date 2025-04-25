<?php

namespace App\Observers;

use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Log;

class SurveyResponseObserver
{
    /**
     * Handle the SurveyResponse "created" event.
     */
    public function created(SurveyResponse $surveyResponse): void
    {
        Log::info("Survey response created by User ID: {$surveyResponse->user_id}, Q: {$surveyResponse->question_id}, Score: {$surveyResponse->score}");
    }

    /**
     * Handle the SurveyResponse "updated" event.
     */
    public function updated(SurveyResponse $surveyResponse): void
    {
        Log::info("Survey response updated  by User ID: {$surveyResponse->user_id}, Q: {$surveyResponse->question_id}, Score: {$surveyResponse->score}");
    }

    /**
     * Handle the SurveyResponse "deleted" event.
     */
    public function deleted(SurveyResponse $surveyResponse): void
    {
        //
    }

    /**
     * Handle the SurveyResponse "restored" event.
     */
    public function restored(SurveyResponse $surveyResponse): void
    {
        //
    }

    /**
     * Handle the SurveyResponse "force deleted" event.
     */
    public function forceDeleted(SurveyResponse $surveyResponse): void
    {
        //
    }
}
