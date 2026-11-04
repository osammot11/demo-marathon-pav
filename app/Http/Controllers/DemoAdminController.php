<?php

namespace App\Http\Controllers;

use App\Models\DemoDiscountCode;
use App\Models\DemoSetting;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\DemoRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DemoAdminController extends Controller
{
    public function __construct(private DemoRegistry $registry) {}

    public function dashboard(Request $request)
    {
        $site = $this->site($request);
        $query = $this->tickets($site['slug'], $request);
        $tickets = $query->latest('tickets.created_at')->paginate(30)->withQueryString();
        $discounts = DemoDiscountCode::where('demo_slug', $site['slug'])->orderBy('code')->get();
        $prices = [];
        foreach ($site['races'] as $race => $_) $prices[$race] = $this->registry->price($site, $race);
        $operations = data_get($site, 'operations', []);
        $operationalCounts = [];
        if ($operations) {
            $base = Ticket::whereHas('order', fn ($q) => $q->where('demo_slug', $site['slug']));
            $operationalCounts = [
                'paid' => Order::where('status', 'paid')->count(),
                'documents_pending' => (clone $base)->where('eligibility_status', 'pending_review')->count(),
                'shuttle_requested' => (clone $base)->where('shuttle_needed', true)->count(),
                'shuttle_assigned' => (clone $base)->whereNotNull('shuttle_assignment')->count(),
                'bib_collected' => (clone $base)->whereNotNull('bib_collected_at')->count(),
            ];
        }

        return view('demo.admin.dashboard', [
            'demos' => $this->registry->all(), 'site' => $site,
            'tickets' => $tickets, 'discounts' => $discounts, 'prices' => $prices,
            'totalTickets' => Ticket::whereHas('order', fn ($q) => $q->where('demo_slug', $site['slug']))->count(),
            'operations' => $operations, 'operationalCounts' => $operationalCounts,
        ]);
    }

    public function edit(Request $request, int $ticket)
    {
        $site = $this->site($request);
        return view('demo.admin.edit', ['site' => $site, 'ticket' => $this->ticket($site, $ticket)]);
    }

    public function update(Request $request, int $ticket)
    {
        $site = $this->site($request);
        $record = $this->ticket($site, $ticket);
        $rules = [
            'first_name' => 'required|string|max:255', 'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255', 'phone' => 'nullable|string|max:30',
            'membership_number' => 'nullable|string|max:100',
            'medical_certificate_expires_on' => 'nullable|date',
        ];
        if (data_get($site, 'operations.shuttle')) {
            $rules += [
                'shuttle_needed' => 'required|boolean',
                'shuttle_assignment' => 'nullable|string|max:100',
                'shuttle_boarded' => 'required|boolean',
            ];
        }
        if (data_get($site, 'operations.bib_pickup')) {
            $rules += [
                'bib_number' => ['nullable', 'string', 'max:30', Rule::unique('tickets', 'bib_number')->where('demo_slug', $site['slug'])->ignore($record->id)],
                'bib_collected' => 'required|boolean',
            ];
        }
        $validated = $request->validate($rules);
        $updates = array_intersect_key($validated, array_flip([
            'first_name', 'last_name', 'email', 'phone', 'membership_number', 'medical_certificate_expires_on',
        ]));
        if (data_get($site, 'operations.shuttle')) {
            if ($validated['shuttle_boarded'] && (! $validated['shuttle_needed'] || blank($validated['shuttle_assignment'] ?? null))) {
                return back()->withInput()->withErrors(['shuttle_boarded' => 'Assegna una navetta prima di segnare l’imbarco.']);
            }
            $updates['shuttle_needed'] = (bool) $validated['shuttle_needed'];
            $updates['shuttle_assignment'] = $validated['shuttle_needed'] ? ($validated['shuttle_assignment'] ?? null) : null;
            $updates['shuttle_boarded_at'] = $validated['shuttle_boarded'] ? ($record->shuttle_boarded_at ?? now()) : null;
        }
        if (data_get($site, 'operations.bib_pickup')) {
            if ($validated['bib_collected'] && (blank($validated['bib_number'] ?? null) || $record->order->status !== 'paid')) {
                return back()->withInput()->withErrors(['bib_collected' => 'Assegna un pettorale a un ordine pagato prima di segnarne il ritiro.']);
            }
            $updates['bib_number'] = $validated['bib_number'] ?? null;
            $updates['bib_collected_at'] = $validated['bib_collected'] ? ($record->bib_collected_at ?? now()) : null;
        }
        $record->update($updates);
        return redirect()->route('admin.ticket.edit', ['demo' => $site['slug'], 'ticket' => $record->id])->with('success', 'Participant updated.');
    }

    public function approve(Request $request, int $ticket)
    {
        $site = $this->site($request);
        $record = $this->ticket($site, $ticket);
        abort_unless($record->order->status === 'paid', 403);
        if ($record->status !== 'active') {
            $record->update([
                'status' => 'active', 'eligibility_status' => 'approved',
                'unique_ticket_code' => $record->unique_ticket_code ?? 'TKT_'.strtoupper(Str::random(16)),
            ]);
        }
        return redirect()->route('admin.ticket.edit', ['demo' => $site['slug'], 'ticket' => $record->id])->with('success', 'Ticket approved. No email was sent.');
    }

    public function document(Request $request, int $ticket)
    {
        $site = $this->site($request);
        $record = $this->ticket($site, $ticket);
        $path = $record->medical_certificate_path;
        abort_unless($path && str_starts_with($path, 'demo-documents/'.$site['slug'].'/') && Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->download($path);
    }

    public function create(Request $request)
    {
        $site = $this->site($request);
        $validated = $request->validate([
            'race' => ['required', Rule::in(array_keys($site['races']))],
            'first_name' => 'required|string|max:255', 'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        DB::transaction(function () use ($site, $validated) {
            $order = Order::create([
                'demo_slug' => $site['slug'], 'race_slug' => $validated['race'],
                'group_code' => 'MANUAL_'.strtoupper(Str::random(14)),
                'total_amount' => 0, 'payment_method' => 'manual_demo',
                'status' => 'paid', 'locale' => 'it',
            ]);
            $this->createTicket($order, $validated, $site);
        });
        return redirect()->route('admin.dashboard', ['demo' => $site['slug']])->with('success', 'Test ticket created. No email was sent.');
    }

    public function import(Request $request)
    {
        $site = $this->site($request);
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:2048']);
        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        abort_unless($handle, 422);
        try {
            $firstLine = fgets($handle);
            abort_unless($firstLine !== false, 422);
            $delimiter = str_contains($firstLine, ';') ? ';' : ',';
            rewind($handle);
            $header = fgetcsv($handle, 0, $delimiter);
            if ($header) $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            abort_unless($header && count(array_intersect(['first_name', 'last_name', 'email', 'race'], $header)) === 4, 422);
            $rows = [];
            while (($values = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (! array_filter($values, fn ($value) => trim((string) $value) !== '')) continue;
                abort_if(count($rows) >= 400, 422, 'Maximum 400 rows per import.');
                abort_if(count($values) > count($header), 422, 'CSV row has too many columns.');
                $row = array_combine($header, array_pad($values, count($header), ''));
                abort_unless($row, 422);
                $rows[] = Validator::make($row, [
                    'first_name' => 'required|string|max:255', 'last_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255', 'race' => ['required', Rule::in(array_keys($site['races']))],
                ])->validate();
            }
        } finally {
            fclose($handle);
        }
        abort_if($rows === [], 422, 'CSV contains no entries.');

        DB::transaction(function () use ($site, $rows) {
            $order = Order::create([
                'demo_slug' => $site['slug'], 'race_slug' => null,
                'group_code' => 'CSV_'.strtoupper(Str::random(14)),
                'total_amount' => 0, 'payment_method' => 'import_demo',
                'status' => 'paid', 'locale' => 'it',
            ]);
            foreach ($rows as $row) $this->createTicket($order, $row, $site);
        });
        return redirect()->route('admin.dashboard', ['demo' => $site['slug']])->with('success', count($rows).' test tickets imported. No email was sent.');
    }

    public function price(Request $request)
    {
        $site = $this->site($request);
        $validated = $request->validate([
            'race' => ['required', Rule::in(array_keys($site['races']))],
            'price' => 'required|numeric|min:0.50|max:999999',
        ]);
        $largestDiscount = (float) (DemoDiscountCode::max('amount') ?? 0);
        if ((float) $validated['price'] < $largestDiscount + 0.50) {
            return back()->withErrors(['price' => 'Price must leave at least EUR 0.50 after the largest discount.']);
        }
        DemoSetting::updateOrCreate(
            ['demo_slug' => $site['slug'], 'key' => 'price.'.$validated['race']],
            ['value' => number_format((float) $validated['price'], 2, '.', '')],
        );
        return redirect()->route('admin.dashboard', ['demo' => $site['slug']])->with('success', 'Test price updated.');
    }

    public function discount(Request $request)
    {
        $site = $this->site($request);
        $validated = $request->validate([
            'code' => 'required|string|alpha_dash|max:100',
            'amount' => 'required|numeric|min:0.01|max:999999',
        ]);
        $minimum = min(array_map(fn ($race) => $this->registry->price($site, $race), array_keys($site['races'])));
        if ((float) $validated['amount'] > $minimum - 0.50) {
            return back()->withErrors(['amount' => 'Discount must leave at least EUR 0.50 on every race.']);
        }
        DemoDiscountCode::updateOrCreate(
            ['demo_slug' => $site['slug'], 'code' => strtoupper($validated['code'])],
            ['amount' => $validated['amount']],
        );
        return redirect()->route('admin.dashboard', ['demo' => $site['slug']])->with('success', 'Discount code saved.');
    }

    public function deleteDiscount(Request $request, int $discount)
    {
        $site = $this->site($request);
        DemoDiscountCode::where('demo_slug', $site['slug'])->findOrFail($discount)->delete();
        return redirect()->route('admin.dashboard', ['demo' => $site['slug']])->with('success', 'Discount code deleted.');
    }

    public function export(Request $request)
    {
        $site = $this->site($request);
        $tickets = $this->tickets($site['slug'], $request)->orderBy('tickets.id')->cursor();
        $hasOperations = (bool) data_get($site, 'operations');
        return response()->streamDownload(function () use ($tickets, $hasOperations) {
            $output = fopen('php://output', 'w');
            $header = ['ticket_code', 'order_code', 'race', 'first_name', 'last_name', 'email', 'status', 'price_paid'];
            if ($hasOperations) $header = array_merge($header, ['documents_status', 'shuttle_needed', 'shuttle_assignment', 'shuttle_boarded_at', 'bib_number', 'bib_collected_at']);
            fputcsv($output, $header);
            foreach ($tickets as $ticket) {
                $values = [$ticket->unique_ticket_code, $ticket->order->group_code, $ticket->route_choice,
                    $ticket->first_name, $ticket->last_name, $ticket->email, $ticket->status, $ticket->price_paid];
                if ($hasOperations) $values = array_merge($values, [
                    $ticket->eligibility_status, $ticket->shuttle_needed ? 'yes' : 'no',
                    $ticket->shuttle_assignment, $ticket->shuttle_boarded_at,
                    $ticket->bib_number, $ticket->bib_collected_at,
                ]);
                fputcsv($output, array_map(fn ($value) => preg_match('/^[=+@-]/', (string) $value) ? "'".$value : $value, $values));
            }
            fclose($output);
        }, 'demo-'.$site['slug'].'-tickets.csv', ['Content-Type' => 'text/csv']);
    }

    private function site(Request $request): array
    {
        $all = $this->registry->all();
        abort_if($all === [], 404);
        $slug = $request->input('demo', isset($all['cro-trail']) ? 'cro-trail' : array_key_first($all));
        abort_unless(is_string($slug) && isset($all[$slug]), 404);
        return $all[$slug];
    }

    private function ticket(array $site, int $id): Ticket
    {
        return Ticket::with('order')->whereHas('order', fn ($q) => $q->where('demo_slug', $site['slug']))->findOrFail($id);
    }

    private function tickets(string $slug, Request $request)
    {
        return Ticket::with('order')->whereHas('order', fn ($q) => $q->where('demo_slug', $slug))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->input('search');
                $q->where(fn ($nested) => $nested->where('first_name', 'like', '%'.$term.'%')
                    ->orWhere('last_name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('unique_ticket_code', 'like', '%'.$term.'%'));
            });
    }

    private function createTicket(Order $order, array $data, array $site): void
    {
        $race = $this->registry->race($site, $data['race']);
        $order->tickets()->create([
            'first_name' => $data['first_name'], 'last_name' => $data['last_name'],
            'email' => $data['email'], 'route_choice' => $race['name'],
            'dob' => '1990-01-01', 'birth_place' => 'N/A', 'residence_address' => 'N/A',
            'city' => 'N/A', 'zip_code' => 'N/A', 'province' => 'N/A', 'region' => 'N/A',
            'country' => 'N/A', 'nationality' => 'IT', 'phone' => 'N/A',
            'tshirt_size' => 'N/A', 'shuttle_needed' => false, 'celiac' => false,
            'price_paid' => 0, 'payment_tag' => 'Admin test', 'status' => 'active',
            'eligibility_status' => 'not_required',
            'unique_ticket_code' => 'TKT_'.strtoupper(Str::random(16)),
        ]);
    }
}
