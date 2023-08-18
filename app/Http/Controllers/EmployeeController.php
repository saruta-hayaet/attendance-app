<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        $employees = User::all();

        return view('employee',compact('employees'));
    }
}
