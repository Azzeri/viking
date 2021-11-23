<?php

namespace App\Http\Controllers;

use App\Models\StoreRequest;
use Illuminate\Http\Request;

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
        ]);

        $query = StoreRequest::query();

        if (request('search')) {
            $query->where('description', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_name', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_email', 'ILIKE', '%' . request('search') . '%')
                ->orWhere('client_phone', 'ILIKE', '%' . request('search') . '%');
        }

        if (request('filter') && request('filter') != 2) {
            $query->where('is_accepted', request('filter'))->where('is_finished', false);
        } else if (request('filter') && request('filter') == 2)
            $query->where('is_finished', true);
        else    
            $query->where('is_accepted', false);


        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('created_at');

        $requests = $query->paginate()->withQueryString()
            ->through(fn ($storeRequest) => [
                'id' => $storeRequest->id,
                'description' => $storeRequest->description,
                'created_at' => $storeRequest->created_at,
                'client_name' => $storeRequest->client_name,
                'client_phone' => $storeRequest->client_phone,
                'client_email' => $storeRequest->client_email,
                'is_finished' => $storeRequest->is_finished,
                'is_accepted' => $storeRequest->is_accepted,
                'store_item_id' => $storeRequest->store_item_id,
                'store_item_name' => $storeRequest->item->name
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreRequest  $storeRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $storeRequest = StoreRequest::find($id);
        $this->authorize('delete', $storeRequest, StoreRequest::class);

        $storeRequest->delete();

        return redirect()->back()->with('message', 'Pomyślnie odrzucono zamówienie');
    }

    public function accept($id)
    {
        $storeRequest = StoreRequest::find($id);

        $this->authorize('update', $storeRequest, StoreRequest::class);

        $storeRequest->is_accepted = true;
        $storeRequest->save();

        return redirect()->back()->with('message', 'Pomyślnie zaakceptowano zamówienie');
    }

    public function finish($id)
    {
        $storeRequest = StoreRequest::find($id);

        $this->authorize('update', $storeRequest, StoreRequest::class);

        $storeRequest->is_finished = true;
        $storeRequest->save();

        return redirect()->back()->with('message', 'Pomyślnie zakończono zamówienie');
    }
}
