<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_demos_have_distinct_pages_and_locales(): void
    {
        $this->get('/')->assertOk()->assertDontSee('CroTrail')->assertDontSee('Coast Run');
        $this->get('/cro-trail')->assertOk()->assertSee('Dalle Alpi')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/cro-trail/en')->assertOk()->assertSee('From the Alps');
        $this->get('/cro-trail/contatti')->assertOk()->assertSee('Parliamone');
        $this->get('/coast-run')->assertOk()->assertSee('Corri verso il mare');
        $this->get('/coast-run/en')->assertOk()->assertSee('Run toward the sea');
        $this->get('/coast-run/contatti')->assertOk()->assertSee('Questo evento è una demo');
        $this->get('/coast-run/privacy')->assertOk()->assertSee('Non inserire dati personali reali');
        $this->get('/coast-run/gare/42')->assertOk()->assertSee('€ 39,00');
        $this->get('/coast-run/iscrizione/10')->assertOk()->assertSee('€ 19,00');
        $this->get('/cro-trail/assets/site.js')->assertOk();
        $this->get('/cro-trail/assets/images/alpi-riviera.jpg')->assertOk();
        $this->get('/coast-run/assets/site.css')->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=utf-8');
        $this->get('/cro-trail/assets/site.js')->assertOk()
            ->assertHeader('Content-Type', 'text/javascript; charset=utf-8');
    }
}
