<?php

namespace Fungku\HubSpot\Tests\Integration\Api;

use Fungku\HubSpot\Api\Engagements;
use Fungku\HubSpot\Api\Contacts;
use Fungku\HubSpot\Http\Client;

class EngagementsTest extends \PHPUnit_Framework_TestCase
{
    private $engagements;

    public function setUp()
    {
        parent::setUp();
        $this->contacts = new Contacts('demo', new Client());
        $this->engagements = new Engagements('demo', new Client());
        sleep(1);
    }

    /*
     * Lots of tests need an existing object to modify.
     */
    private function createEngagement()
    {
        sleep(1);
        $contact = $this->contacts->create([]);
        $response = $this->engagements->create([
            "active" => true,
            "ownerId" => 1,
            "type" => "NOTE",
            "timestamp" => 1409172644778,
        ], [
            "contactIds" => [$contact->vid],
            "companyIds" => [],
            "dealIds" => [],
            "ownerIds" => [],
        ], [
            'body' => 'note body',
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        return $response;
    }

    /** @test */
    public function update()
    {
        $engagement = $this->createEngagement();
        $contact = $this->contacts->create([]);
        $response = $this->engagements->update($engagement->engagement->id, [
            "active" => true,
            "ownerId" => 1,
            "type" => "NOTE",
            "timestamp" => 1409172644778
        ], [
            'body' => 'note body',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function delete()
    {
        $engagement = $this->createEngagement();

        $response = $this->engagements->delete($engagement->engagement->id);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function get()
    {
        $engagement = $this->createEngagement();

        $response = $this->engagements->get($engagement->engagement->id);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function associate()
    {
        $engagement = $this->createEngagement();
        $contact = $this->contacts->create([]);
        $response = $this->engagements->associate($engagement->engagement->id, 'contact', $contact->vid);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
