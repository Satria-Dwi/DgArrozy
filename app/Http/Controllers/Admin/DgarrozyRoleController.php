<?php

namespace App\Http\Controllers\admin;

use App\Models\DgarrozyRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DgarrozyRoleController extends Controller
{
    public function index()
    {
        return response()->json(DgarrozyRole::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:dgarrozy_role,code',
            'name' => 'required'
        ]);

        $role = DgarrozyRole::create($request->only('code', 'name'));

        return response()->json($role, 201);
    }
}
