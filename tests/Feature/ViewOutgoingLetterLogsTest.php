<?php

namespace Tests\Feature;

use App\OutgoingLetterLog;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

class ViewOutgoingLetterLogsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function guest_cannot_view_letter_logs()
    {
        $this->get('/outgoing-letter-logs')
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_view_outgoing_letter_logs()
    {
        $this->be(factory(User::class)->create());
        factory(OutgoingLetterLog::class, 3)->create();
        
        $viewLetterLogs = $this->withoutExceptionHandling()
            ->get('/outgoing-letter-logs')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letter_logs.index')
            ->assertViewHas('outgoing_letter_logs')
            ->viewData('outgoing_letter_logs');

        $this->assertInstanceOf(Collection::class, $viewLetterLogs);
        $this->assertCount(3, $viewLetterLogs);
    }

    /** @test */
    public function user_can_view_filtered_letter_logs_based_on_before_given_date()
    {
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-10-12']);

        $beforeFilter = '2017-09-01';
        
        $viewLetterLogs = $this->withoutExceptionHandling()
            ->get('/outgoing-letter-logs?before=' . $beforeFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing-letter-logs.index')
            ->assertViewHas('outgoing_letter_logs')
            ->viewData('outgoing_letter_logs');

        $this->assertInstanceOf(Collection::class, $viewLetterLogs);
        $this->assertCount(2, $viewLetterLogs, 'Only 2 logs were expected but :actual logs were returned');

        $logsAfterBeforeFilter = $viewLetterLogs->filter(function($log) use ($beforeFilter){
            return \Carbon::parse($beforeFilter)->lessThan($log->date);
        });
        
        $this->assertCount(0, $logsAfterBeforeFilter, 'filtered logs do not respect `before` filter');
    }

    /** @test */
    public function user_can_view_filtered_letter_logs_based_on_after_given_date()
    {
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-10-12']);

        $afterFilter = '2017-09-01';
        
        $viewLetterLogs = $this->withoutExceptionHandling()
            ->get('/outgoing-letter-logs?after=' . $afterFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing-letter-logs.index')
            ->assertViewHas('outgoing_letter_logs')
            ->viewData('outgoing_letter_logs');

        $this->assertInstanceOf(Collection::class, $viewLetterLogs);
        $this->assertCount(1, $viewLetterLogs, 'Only 1 log was expected but :actual logs were returned');

        $logsBeforeAfterFilter = $viewLetterLogs->filter(function($log) use ($afterFilter){
            return \Carbon::parse($afterFilter)->greaterThan($log->date);
        });
        
        $this->assertCount(0, $logsBeforeAfterFilter, 'filtered logs do not respect `after` filter');
    }

    /** @test */
    public function user_can_view_filtered_letter_logs_based_on_before_and_after_given_date()
    {
        factory(OutgoingLetterLog::class)->create(['date' => '2017-07-20']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetterLog::class)->create(['date' => '2017-10-12']);

        $afterFilter = '2017-08-01';
        $beforeFilter = '2019-09-01';
        
        $viewLetterLogs = $this->withoutExceptionHandling()
            ->get('/outgoing-letter-logs?after=' . $afterFilter . '&before='. $beforeFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing-letter-logs.index')
            ->assertViewHas('outgoing_letter_logs')
            ->viewData('outgoing_letter_logs');

        $this->assertInstanceOf(Collection::class, $viewLetterLogs);
        $this->assertCount(2, $viewLetterLogs, 'Only 2 logs were expected but :actual logs were returned');

        $logsOutOfFilterRange = $viewLetterLogs->filter(function($log) use ($afterFilter, $beforeFilter){
            return \Carbon::parse($beforeFilter)->lessThan($log->date)
                && \Carbon::parse($afterFilter)->greaterThan($log->date);
        });
        
        $this->assertCount(0, $logsOutOfFilterRange, 'filtered logs do not respect `before` and `after` filter');
    }
}
