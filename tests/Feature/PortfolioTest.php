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
        $response->assertDontSee('Belum ada anggota tim yang ditambahkan');
    }
}
