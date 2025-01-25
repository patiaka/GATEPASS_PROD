<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Helper\DeleteAction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use DeleteAction;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
        flash('User created with success!');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $department = Department::all();
        return view('user.update', compact('user', 'department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        flash('User updated with success!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user)
    {
        $delete = User::where('id', $user)->update(['status' => false]);

        return response()->json([
            'success' => true,
            'message' => $delete ? 'user desactivated with success' : 'user not found',
        ]);
    }
}
