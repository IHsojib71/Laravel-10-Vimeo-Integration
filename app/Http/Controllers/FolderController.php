<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = Folder::paginate(10);
        return view('folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('folders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = $request->validate(['title' => ['required', 'unique:folders','string:255']]);
        if(Folder::create($valid))
            return to_route('folder.index')->with('success', 'New Folder Created Successfully!');

        return back()->with('error', 'Something went wrong!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Folder $folder)
    {
        return view('folders.edit',compact('folder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Folder $folder)
    {
        $valid = $request->validate(['title' => ['required', 'string:255']]);
        if($folder->update($valid))
            return to_route('folder.index')->with('success', 'Folder Updated Successfully!');

        return back()->with('error', 'Something went wrong!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
        if($folder->delete())
            return to_route('folder.index')->with('success', 'New Folder Created Successfully!');

        return back()->with('error', 'Something went wrong!');
    }
}
