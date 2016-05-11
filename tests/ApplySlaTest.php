<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApplySlaTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_applies_sla()
    {
        $category = \App\Category::find(2);

        $sla = App\Sla::create(['name' => 'Test SLA', 'due_days' => 1, 'due_hours' => 0, 'due_minutes' => 0, 'response_days' => 0, 'response_hours' => 4, 'response_minutes' => 0]);
        $sla->updateCriteria([[
            'field' => 'category_id',
            'value' => $category->id,
            'operator' => 'is',
            'labels' => 'Helpdesk'
        ]]);

        $data = [
            'subject' => 'Test Subject', 'description' => 'Test description here',
            'urgency_id' => 1, 'priority_id' => 1, 'impact_id' => 1,
            'group_id' => 1, 'technician_id' => 1, 'category_id' => $category->id, 'subcategory_id' => $category->subcategories()->first(),
            'item_id' => ''
        ];

        $this->post(route('ticket.store'), $data)->assertRedirectedToRoute('ticket.index');

        $ticket = \App\Ticket::latest()->first();

        $this->assertEquals($sla->id, $ticket->sla_id);
        $this->assertTrue(\Carbon\Carbon::now()->addHours(4)->eq($ticket->first_response_date));
    }
}
