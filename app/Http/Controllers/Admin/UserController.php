<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\StoreDetail;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('user_access');
        $query = User::query();

        $keyword = $request->input('keyword', '');
        $query->where(function ($query1) use ($keyword) {
            $query1->where('name', 'like', '%'.$keyword.'%')
            ->orwhere('email', 'like', '%'.$keyword.'%');
        });

        if(isset($request->items)){
            $data['items'] = $request->items;
        }
        else{
            $data['items'] = 10;
        }
        
        $data['roles'] = Role::pluck('title','id');
        $data['data'] = $query->orderBy('created_at','DESC')->paginate($data['items']);

        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['roles'] = Role::all()->pluck('title', 'id');
       
        return view('admin.user.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            if($data['role'] == 2) {
                StoreDetail::create(['name' => $data['store_name'], 'user_id' => $user->id]);
            }
            $user->roles()->sync($data['role']);
            
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $data['data'] = $user->load('store', 'roles');
        return view('admin.user.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $data['data'] = $user->load('store', 'roles');
        $data['roles'] = Role::all()->pluck('title', 'id');
        return view('admin.user.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        
        // return redirect()->route('products.index')->with('success', 'Product updated successfully');
        DB::beginTransaction();
        try {
            
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);
            if(isset($data['store_name']))
                $user->store->update(['name' => $data['store_name']]);

            if (isset($data['store_name'])) {
                if ($user->store) {
                    $user->store->update(['name' => $data['store_name']]);
                } else {
                    if ($data['role'] == 2) {
                        $user->store()->create(['name' => $data['store_name'], 'user_id' => $user->id]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
