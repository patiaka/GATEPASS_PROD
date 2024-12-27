<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Http\Requests\StoreMaterialRequestRequest;
use App\Http\Requests\UpdateMaterialRequestRequest;

class MaterialRequestController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialRequest $materialRequest)
    {
        return \view('material.show', \compact('materialRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaterialRequest $materialRequest)
    {
        return \view('material.update', \compact('materialRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequestRequest $request, MaterialRequest $materialRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialRequest $materialRequest)
    {
        //
    }
}
