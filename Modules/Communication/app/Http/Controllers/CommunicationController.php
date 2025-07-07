<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;

class CommunicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('communication::index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('communication::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
    }

    /**
     * Show the specified resource.
     */
    public function show()
    {
        return view('communication::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('communication::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
    }
}
