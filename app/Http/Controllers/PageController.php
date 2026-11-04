<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Throwable;

class PageController extends Controller
{
    private const PAGES = [
        '' => [
            'view' => 'pages.home',
            'title' => 'Maratona Alzheimer 2026 | Insieme per cancellare il silenzio',
            'description' => 'Maratona Alzheimer 2026: Mezza Maratona e marce solidali tra Cesena e Cesenatico il 12 e 13 settembre.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/',
            'bodyClass' => 'home-page',
        ],
        'contattaci' => [
            'view' => 'pages.contattaci',
            'title' => 'Contatti | Maratona Alzheimer 2026',
            'description' => 'Contatta lo staff di Maratona Alzheimer per informazioni su iscrizioni, percorsi e Fondazione.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/contatti/',
        ],
        'contatti' => [
            'view' => 'pages.contattaci',
            'title' => 'Contatti | Maratona Alzheimer 2026',
            'description' => 'Contatta lo staff di Maratona Alzheimer per informazioni su iscrizioni, percorsi e Fondazione.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/contatti/',
        ],
        'cookie-policy' => [
            'view' => 'pages.cookie-policy',
            'title' => 'Cookie Policy | Maratona Alzheimer',
            'description' => 'Informativa sull\'uso dei cookie del sito Maratona Alzheimer.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/cookie-policy/',
        ],
        'partnership' => [
            'view' => 'pages.partnership',
            'title' => 'Partner | Maratona Alzheimer 2026',
            'description' => 'I partner e le istituzioni che sostengono Maratona Alzheimer 2026.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/partners-2026/',
        ],
        'privacy-policy' => [
            'view' => 'pages.privacy-policy',
            'title' => 'Privacy Policy | Maratona Alzheimer',
            'description' => 'Informativa privacy per iscrizioni e contatti del sito Maratona Alzheimer.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/privacy/',
        ],
        'regolamento' => [
            'view' => 'pages.regolamento',
            'title' => 'Regolamento | Maratona Alzheimer 2026',
            'description' => 'Informazioni essenziali per partecipare alla Mezza Maratona e alle Marce Alzheimer 2026.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/maratona/regolamento-mezza-maratona-alzheimer-2026/',
        ],
        'termini-condizioni' => [
            'view' => 'pages.regolamento',
            'title' => 'Regolamento | Maratona Alzheimer 2026',
            'description' => 'Informazioni essenziali per partecipare alla Mezza Maratona e alle Marce Alzheimer 2026.',
            'canonical' => 'https://www.maratonaalzheimer.it/it/maratona/regolamento-mezza-maratona-alzheimer-2026/',
        ],
    ];

    private const HOME_ALIASES = [
        'come-arrivare',
        'gallery',
        'orario',
        'percorsi',
        'ricettivita',
        'styleguide',
    ];

    public function show(?string $slug = ''): Response
    {
        $slug = trim((string) $slug, '/');
        $slug = Str::replaceEnd('.php', '', $slug);

        if (in_array($slug, self::HOME_ALIASES, true)) {
            $slug = '';
        }

        abort_unless(array_key_exists($slug, self::PAGES), 404);

        $page = self::PAGES[$slug];
        $data = [
            'title' => $page['title'],
            'description' => $page['description'],
            'canonical' => $page['canonical'],
            'bodyClass' => $page['bodyClass'] ?? '',
        ];

        if ($page['view'] === 'pages.home') {
            try {
                $data['totalIscritti'] = Ticket::whereNotNull('unique_ticket_code')->count();
            } catch (Throwable $e) {
                $data['totalIscritti'] = 0;
            }
        }

        return response()->view($page['view'], $data);
    }
}
