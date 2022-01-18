<?php

namespace App\Http\Controllers;

use App\Models\Privilege;
use App\Models\StoreItem;
use App\Models\StoreRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StoreRequest::class);

        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:created_at,store_item_id,client_name,id'],
            'filter' => ['in:0,1,2']
        ]);

        $query = StoreRequest::query();

        if (request('search')) {
            $query->where('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_email', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_phone', 'ILIKE', '%' . request('search') . '%');
        }

        switch (request('filter')) {
            case 0:
            default:
                $query->where('is_accepted', true)->where('is_finished', false);
                break;
            case 1:
                $query->where('is_accepted', false)->where('is_finished', false);
                break;
            case 2:
                $query->where('is_finished', true);
                break;
        }

        if (request()->has(['field', 'direction'])) {
            if (request('field') == 'store_item_id')
                $query->orderBy(StoreItem::select('name')->whereColumn('store_items.id', 'store_requests.store_item_id'), request('direction'));
            else
                $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('created_at', 'desc');

        if (Auth::user()->privilege_id != Privilege::IS_ADMIN)
            $query->whereHas('item', function ($q) {
                $q->whereJsonContains('craftspeople', Auth::user()->id);
            });

        $requests = $query->paginate(10)->withQueryString()
            ->through(fn ($storeRequest) => [
                'id' => $storeRequest->id,
                'description' => $storeRequest->description,
                'created_at' => Carbon::parse($storeRequest->created_at)->locale('pl')->isoFormat('Do MMM YYYY'),
                'client_name' => $storeRequest->client_name,
                'client_phone' => $storeRequest->client_phone,
                'client_email' => $storeRequest->client_email,
                'is_finished' => $storeRequest->is_finished,
                'is_accepted' => $storeRequest->is_accepted,
                'store_item_id' => $storeRequest->store_item_id,
                'store_item_name' => $storeRequest->item->name,
                'note' => $storeRequest->note,
                'date_finished' => Carbon::parse($storeRequest->date_finished)->locale('pl')->isoFormat('Do MMM YYYY'),
            ]);

        return inertia('Admin/StoreRequests', [
            'requests' => $requests,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        StoreRequest::create(
            $request->validate([
                'store_item_id' => ['required', 'integer', 'min:1',],
                'description' => ['nullable', 'min:1', 'max:255'],
                'client_name' => ['required', 'string', 'min:3', 'max:64'],
                'client_phone' => ['nullable', 'string'],
                'client_email' => ['required', 'email:filter']
            ])
        );

        return redirect()->back()->with('message', 'Wysłano dane!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreRequest  $storeRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreRequest $store_request)
    {
        $this->authorize('delete', $store_request, StoreRequest::class);

        $store_request->delete();

        return redirect()->back()->with('message', 'Pomyślnie usunięto zamówienie');
    }

    /**
     * Marks the specified resource as accepted
     *
     * @param  \App\Models\StoreRequest  $storeRequest
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, StoreRequest $store_request)
    {
        $this->authorize('update', $store_request, StoreRequest::class);

        $request->validate([
            'note' => ['nullable', 'min:1', 'max:255'],
        ]);

        $store_request->update([
            'note' => $request->note,
            'is_accepted' => true
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zaakceptowano zamówienie');
    }

    /**
     * Marks the specified resource as finished
     *
     * @param  \App\Models\StoreRequest  $storeRequest
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(StoreRequest $store_request)
    {
        $this->authorize('update', $store_request, StoreRequest::class);

        $store_request->update([
            'date_finished' => Carbon::now(),
            'is_finished' => true
        ]);

        return redirect()->back()->with('message', 'Pomyślnie zakończono zamówienie');
    }
}
