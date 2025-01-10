<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\Compagnie;
use Illuminate\Http\Request;

class CompagnieController extends Controller
{
    use DeleteAction;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Compagnie::all();
        return \view('compagnie.index', \compact('rows'));
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
        $request->validate(['name' => 'required|string|max:50']);
        Compagnie::create(['name' => $request->name]);
        flash('Compagnie created with success!');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Compagnie $compagnie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compagnie $compagnie)
    {
        return view('compagnie.update', compact('compagnie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compagnie $compagnie)
    {
        $compagnie->update($request->validate(['name' => 'required|string|max:50']));
        flash('compagnie updated with success!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $compagnie)
    {
        $delete = Compagnie::findOrFail($compagnie);

        return $this->supp($delete);
    }
}
