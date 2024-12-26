<?php

namespace App\Http\Controllers;

use App\Helper\DeleteAction;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use DeleteAction;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Department::all();
        return \view('department.index', \compact('rows'));
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
        Department::create(['name' => $request->name]);
        flash('Department created with success!');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('department.update', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $department->update($request->validate(['name' => 'required|string|max:50']));
        flash('Department updated with success!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $department)
    {
        $delete = Department::findOrFail($department);

        return $this->supp($delete);
    }
}
