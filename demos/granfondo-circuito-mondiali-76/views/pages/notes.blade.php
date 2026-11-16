@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content" class="registration-page notes-page">
    <div class="container registration-shell">
        <div class="registration-heading notes-heading">
            <a class="back-link dark-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => '136']) }}">← {{ __($translationKey.'notes_back') }}</a>
            <p class="eyebrow">{{ __($translationKey.'notes_kicker') }}</p>
            <h1>{{ __($translationKey.'notes_title') }}</h1>
            <p>{{ __($translationKey.'notes_lead') }}</p>
        </div>

        <div class="registration-form-card notes-card">
            <h2>{{ __($translationKey.'notes_basis_title') }}</h2>
            <p>{{ __($translationKey.'notes_basis_text') }}</p>
        </div>

        <div class="registration-form-card notes-card">
            <h2>{{ __($translationKey.'notes_table_title') }}</h2>
            <div class="notes-table" role="table" aria-label="{{ __($translationKey.'notes_table_title') }}">
                <div class="notes-row notes-head" role="row"><span role="columnheader">{{ __($translationKey.'notes_col_seen') }}</span><span role="columnheader">{{ __($translationKey.'notes_col_demo') }}</span></div>
                @foreach(__($translationKey.'notes_items') as $item)
                    <div class="notes-row" role="row"><p role="cell"><b>0{{ $loop->iteration }}</b> {{ $item['seen'] }}</p><p role="cell">{{ $item['demo'] }}</p></div>
                @endforeach
            </div>
        </div>

        <div class="notes-split">
            <div class="registration-form-card notes-card">
                <h2>{{ __($translationKey.'notes_open_title') }}</h2>
                <ul class="check-list">
                    @foreach(__($translationKey.'notes_open') as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="registration-form-card notes-card notes-call">
                <h2>{{ __($translationKey.'notes_call_title') }}</h2>
                <p>{{ __($translationKey.'notes_call_text') }}</p>
                <p class="small-note">{{ __($translationKey.'notes_demo_note') }}</p>
            </div>
        </div>
    </div>
</main>
@endsection
