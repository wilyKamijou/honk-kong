<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=user::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user=user::create($request->all());
        return redirect('/user');
    }

    /**
     * Display the specified resource.
     */
    public function show( $resenas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $user=user::findorFail($id);
        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $user=user::findorFail($id);
        $user->update($request->all());
        return redirect('/user');
    }
    public function cambiarRol($id)
{
    $user = User::findOrFail($id);
    $user->role = $user->role === 'admin' ? 'cliente' : 'admin';
    $user->save();

    return redirect()->back()->with('success', 'Rol cambiado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $user=user::findorfail($id);
        $user->delete();
        return redirect('/user');
    }
}

