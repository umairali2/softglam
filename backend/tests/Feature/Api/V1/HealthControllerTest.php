<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_returns_the_api_health_contract(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'service' => 'softglam-api',
                    'status' => 'ok',
                ],
            ]);
    }

    public function test_returns_a_request_id_header(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            (string) $response->headers->get('X-Request-ID'),
        );
    }
}
