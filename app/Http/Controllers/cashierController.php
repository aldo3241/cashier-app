<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class cashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = [
            ['name' => 'Item 1', 'quantity' => 1],
            ['name' => 'Item 2', 'quantity' => 2],
            ['name' => 'Item 3', 'quantity' => 3],
        ];
        $total = 10.00;

            return view('cashier.cashier', ['items' => $items, 'total' => $total]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
