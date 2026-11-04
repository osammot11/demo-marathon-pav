@extends('demo.admin.layout')
@section('title', 'Ticket')
@section('content')
<p><a href="{{ route('admin.dashboard', ['demo' => $site['slug']]) }}">← Torna a {{ $site['name'] }}</a></p>
<h1>{{ $ticket->first_name }} {{ $ticket->last_name }}</h1>
<p>Ordine {{ $ticket->order->group_code }} · Pagamento {{ $ticket->order->status }} · Ticket {{ $ticket->status }}</p>
<div class="grid">
    <section class="panel">
        <h2>Dati e logistica</h2>
        <form method="post" action="{{ route('admin.ticket.update', ['ticket' => $ticket->id]) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="demo" value="{{ $site['slug'] }}">
            <label>Nome<input name="first_name" value="{{ old('first_name', $ticket->first_name) }}" required></label>
            <label>Cognome<input name="last_name" value="{{ old('last_name', $ticket->last_name) }}" required></label>
            <label>Email<input type="email" name="email" value="{{ old('email', $ticket->email) }}" required></label>
            <label>Telefono<input name="phone" value="{{ old('phone', $ticket->phone) }}"></label>
            <label>Numero tessera<input name="membership_number" value="{{ old('membership_number', $ticket->membership_number) }}"></label>
            <label>Scadenza certificato<input name="medical_certificate_expires_on" type="date" value="{{ old('medical_certificate_expires_on', $ticket->medical_certificate_expires_on) }}"></label>
            @if(data_get($site, 'operations.shuttle'))
                <h3>Navetta Veroli → Sora</h3>
                <input type="hidden" name="shuttle_needed" value="0">
                <label class="status-field"><input type="checkbox" name="shuttle_needed" value="1" @checked(old('shuttle_needed', $ticket->shuttle_needed))> Navetta richiesta</label>
                <label>Assegnazione mezzo / turno<input name="shuttle_assignment" value="{{ old('shuttle_assignment', $ticket->shuttle_assignment) }}" placeholder="Es. Bus A · 05:00"></label>
                <input type="hidden" name="shuttle_boarded" value="0">
                <label class="status-field"><input type="checkbox" name="shuttle_boarded" value="1" @checked(old('shuttle_boarded', (bool) $ticket->shuttle_boarded_at))> Atleta imbarcato</label>
            @endif
            @if(data_get($site, 'operations.bib_pickup'))
                <h3>Ritiro pettorale</h3>
                <label>Numero pettorale<input name="bib_number" value="{{ old('bib_number', $ticket->bib_number) }}" maxlength="30"></label>
                <input type="hidden" name="bib_collected" value="0">
                <label class="status-field"><input type="checkbox" name="bib_collected" value="1" @checked(old('bib_collected', (bool) $ticket->bib_collected_at))> Pettorale consegnato</label>
            @endif
            <button type="submit">Salva stato atleta</button>
        </form>
    </section>
    <section class="panel">
        <h2>Documenti e biglietto</h2>
        <p>Stato documenti: <strong>{{ $ticket->eligibility_status ?? 'non richiesti' }}</strong></p>
        @if($ticket->medical_certificate_path)
            <p><a class="btn" href="{{ route('admin.ticket.document', ['demo' => $site['slug'], 'ticket' => $ticket->id]) }}">Scarica documento privato</a></p>
        @endif
        @if($ticket->order->status === 'paid' && $ticket->status !== 'active')
            <form method="post" action="{{ route('admin.ticket.approve', ['ticket' => $ticket->id]) }}">
                @csrf <input type="hidden" name="demo" value="{{ $site['slug'] }}">
                <button type="submit">Approva documenti e ticket</button>
            </form>
        @endif
        @if($ticket->unique_ticket_code)
            <p>Codice: <strong>{{ $ticket->unique_ticket_code }}</strong></p>
            <a href="{{ route('demo.ticket', ['demo' => $site['slug'], 'unique_code' => $ticket->unique_ticket_code]) }}">Visualizza ticket ↗</a>
        @endif
        <p class="small muted">La demo non invia email reali.</p>
    </section>
</div>
@endsection
