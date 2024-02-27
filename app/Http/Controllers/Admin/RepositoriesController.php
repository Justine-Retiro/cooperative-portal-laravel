<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class RepositoriesController extends Controller
{
    public function index(){

        $clients = Client::all();
        return view('admin.repositories.repositories', compact('clients'));

    }
    public function add(){
        $account_number = "6" . mt_rand(100000000, 999999999);
        $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+'), 0, 9);
        return view('admin.repositories.addrepo', ['account_number' => $account_number, 'password' => $password]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|unique:users,account_number',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
        ]);

        // Assuming you have a Repo model to interact with your database
        $repo = new Client();
        $repo->first_name = $request->input('first_name');
        $repo->middle_name = $request->input('middle_name');
        $repo->last_name = $request->input('last_name');
        $repo->citizenship = $request->input('citizenship');
        $repo->civil_status = $request->input('civil_status');
        $repo->spouse_name = $request->input('spouse_name');
        $repo->birth_date = $request->input('birth_date');
        $repo->birth_place = $request->input('birth_place');
        $repo->provincial_address = $request->input('provincial_address');
        $repo->city_address = $request->input('city_address');
        $repo->mailing_address = $request->input('mailing_address');
        $repo->phone_number = $request->input('phone_num');
        $repo->tax_id_number = $request->input('taxID_num');
        $repo->date_employed = $request->input('date_employed');
        $repo->position = $request->input('position');
        $repo->account_status = $request->input('account_status');
        $repo->nature_of_work = $request->input('natureOf_work');
        $repo->amount_of_share = $request->input('amountOf_share');       
        // User insertion

        $user = new User();
        $role = new Role();
        $role = Role::where('title', 'member')->first();
        $user->role_id = $role->id;
        $user->account_number = $validated['account_number'];
        $user->name = $validated['first_name'] . ' ' . $validated['middle_name'] . ' ' . $validated['last_name'];
        $user->password = bcrypt($validated['password']);
        $user->default_password = true;

        $user->birth_date = $request->input('birth_date');;
        $user->save();

        $repo->user_id = $user->id; 
        $repo->save();

        return redirect()->route('admin.repositories')->with('success', 'Member added successfully.');
    }
    public function edit($id){
        $user = Client::find($id);
        return view('admin.repositories.editrepo', compact('user'));
    }
    public function update(Request $request, $id){
        $user = Client::with('user')->find($id);
        $user->update($request->all());
        return redirect()->route('admin.repositories')->with('success', 'Member updated successfully.');
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');
        $clients = Client::where('first_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                        ->get();

        return view('admin.partials.client_row', compact('clients'));
    }
}
