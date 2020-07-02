<?php

namespace Mabadir\ActivityTracker\Tests;

use ActivityTypeSeeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Mabadir\ActivityTracker\Jobs\CaptureActivityJob;
use Mabadir\ActivityTracker\Models\Activity;
use Mabadir\ActivityTracker\Models\ActivityType;
use Mabadir\ActivityTracker\Models\Visitor;
use Orchestra\Testbench\TestCase;

class ActivityTrackerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Mabadir\ActivityTracker\ActivityTrackerServiceProvider'];
    }

    /**
    * Define environment setup.
    *
    * @param  \Illuminate\Foundation\Application  $app
    * @return void
    */
    protected function getEnvironmentSetUp($app)
    {
        include_once(__DIR__ . "/../database/migrations/create_activities_table.php.stub");
        include_once(__DIR__ . "/../database/migrations/create_activity_types_table.php.stub");
        include_once(__DIR__ . "/../database/migrations/create_visitors_table.php.stub");

        (new \CreateActivitiesTable)->up();
        (new \CreateActivityTypesTable)->up();
        (new \CreateVisitorsTable)->up();

    }

    public function setUp() : void
    {
        parent::setUp();
        $this->visitor_id = (string) Str::uuid();
        $this->payload = [];
        $this->type = 'visit';
        DB::table('activity_types')->insert([
            "name" => 'Page Visit',
            "slug" => 'visit',
        ]);

        DB::table('activity_types')->insert([
            "name" => 'Login',
            "slug" => 'login',
        ]);
    }

        /** @test */
    public function user_can_submit_their_activity()
    {
        $response = $this->withoutExceptionHandling()->post('api/activities', [
            'type' => 'visit',
            'payload' => [
                'page' => '/',
                'user-agent' => "agent123"
                ]
        ]);

        $response->assertSuccessful();
    }


    /** @test */
    public function job_adds_a_new_activity_trail()
    {
        CaptureActivityJob::dispatch([
            'visitor_id' => $this->visitor_id,
            'type' => $this->type,
            'payload' => $this->payload,
            'user_id' => null
        ]);

        $this->assertCount(1, Activity::all());
    }

    /** @test */
    public function type_id_is_captured_correctly()
    {
        $type_id = ActivityType::query()
            ->where('slug', $this->type)
            ->get()->first()->id;
        CaptureActivityJob::dispatch([
            'visitor_id' => $this->visitor_id,
            'type' => $this->type,
            'payload' => $this->payload,
            'user_id' => null
        ]);

        $this->assertEquals($type_id, Activity::first()->activity_type_id);

    }

    /** @test */
    public function new_visitor_creates_new_visitor_record()
    {
        CaptureActivityJob::dispatch([
            'visitor_id' => $this->visitor_id,
            'type' => $this->type,
            'payload' => $this->payload,
            'user_id' => null,
        ]);

        $this->assertCount(1, Visitor::all());
    }
}