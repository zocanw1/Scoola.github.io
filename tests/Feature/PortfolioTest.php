<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortfolioTest extends TestCase
{
    public function test_portfolio_loads_team_members_from_config_without_database(): void
    {
        $response = $this->get(route('portfolio'));

        $response->assertOk();
        $response->assertSee('Muhammad Rafif');
        $response->assertSee('Selky Callista Retnadi');
        $response->assertSee('Pradita Galuh Sendry Pratiwi');
        $response->assertSee('0813-2092-2504');
        $response->assertSee('0857-8647-7368');
        $response->assertSee('0858-0402-4550');
        $response->assertDontSee('Belum ada anggota tim yang ditambahkan');
    }
}
