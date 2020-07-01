<?php

namespace Mabadir\ActivityTracker\Tests;

use ActivityTypeSeeder;
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
        $this->artisan('db:seed', ['--class' => 'ActivityTypeSeeder']);
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
}