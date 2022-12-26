<?php

namespace App\Http\Controllers;

use App\Exports\ExportUser;
use App\Imports\UserImport;
use App\Models\File;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index()
    {
        $files = File::all();
        return view('welcome', compact('files'));
    }

    public function import(Request $request)
    {
        $this->validate(
            $request,
            [
                'file' => 'required|mimes:xlsx',
            ],
            [
                'file.required' => 'excel file is require',
                'file.mimes' => ' file must be excel file',
            ]
        );
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        File::create([
            'file_name' => $file_name,
        ]);
        Excel::import(new UserImport(), $request->file('file'));
        return redirect()->route('/');
    }

    public static function export($id)
    {
        $file_name = File::select('file_name')
            ->where('id', $id)
            ->first();
        return Excel::download(new ExportUser($id), $file_name->file_name);
    }
}
