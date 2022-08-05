<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\File;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new UserImport(), $request->file('file'));
        return 'success';
    }
}
