<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of all clients.
     */
    public function index()
    {
        // Get all clients from database.
        // latest() means newest clients will appear first.
        $clients = Client::latest()->get();

        // Send clients data to the index view.
        return view('backend.clients.index', compact('clients'));
    }


    /**
     * Show the form for creating a new client.
    
     */
    public function create()
    {
        // Show the create client form.
        return view('backend.clients.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                'unique:clients,phone',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:clients,email',
            ],

            'address' => [
                'nullable',
                'string',
            ],

        ], [

            'phone.unique' =>
            'This phone number is already registered. Please use a different phone number.',

            'email.unique' =>
            'This email address is already registered. Please use a different email address.',

            'email.email' =>
            'Please enter a valid email address.',

        ]);


        Client::create($validated);


        return redirect()
            ->route('admin.clients.index')
            ->with(
                'success',
                'Client added successfully.'
            );
    }


    /**
     * Display a specific client.
  
     */
    public function show(Client $client)
    {
        // Send selected client to show page.
        return view('backend.clients.show', compact('client'));
    }


    /**
     * Show the form for editing a client.
   
     */
    public function edit(Client $client)
    {
        // Show edit form with existing client information.
        return view('backend.clients.edit', compact('client'));
    }


    /**
     * Update an existing client.
    
     */
    public function update(Request $request, Client $client)
    {
        // Validate updated information.
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);


        // Update client information.
        $client->update($validated);


        // Return to client list.
        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }


    /**
     * Delete a client.
     */
    public function destroy(Client $client)
    {
        // Delete the selected client.
        $client->delete();


        // Return to client list.
        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
