<?php

namespace Mabadir\ActivityTracker\Jobs;

use Mabadir\ActivityTracker\Models\Activity;
use Mabadir\ActivityTracker\Models\ActivityType;
use Mabadir\ActivityTracker\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CaptureActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $type;
    public $payload;
    public $user_id;
    public $visitor_id;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct(array $data)
    {
        list(
            'type' => $this->type,
            'payload' => $this->payload,
            'visitor_id' => $this->visitor_id,
            'user_id' => $this->user_id
            ) = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Visitor::updateOrCreate(
            ['id' => $this->visitor_id],
            [
                'last_activity_at' => \Carbon\Carbon::now(),
                'user_id' => $this->user_id
            ]
        );

        Activity::create([
            'visitor_id' => $this->visitor_id,
            'activity_type_id' => $this->getActivityTypeId(),
            'payload' => $this->payload,
        ]);
    }

    public function getActivityTypeId()
    {
        return ActivityType::where('slug', $this->type)
            ->first()->id;
    }
}