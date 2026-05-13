<?php

namespace App\Http\Controllers;

use App\Models\PotentialCustomer;
use Illuminate\Http\Request;

class PotentialCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = PotentialCustomer::with('creator')
            ->latest()
            ->paginate(10);

        return view('potential_customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PotentialCustomer $potentialCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PotentialCustomer $potentialCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PotentialCustomer $potentialCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PotentialCustomer $potentialCustomer)
    {
        //
    }
}
