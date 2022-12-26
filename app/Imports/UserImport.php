<?php

namespace App\Imports;

use App\Models\File;
use App\Models\FileColumn;
use App\Models\FileData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // dd($collection);

        $file_id = File::latest()->first();
        for ($i = 0; $i < count($collection[0]); $i++) {
            FileColumn::create([
                'file_id' => $file_id->id,
                'column_name' => $collection[0][$i],
            ]);
        }
        $column_id = FileColumn::all();
        for ($i = 1; $i < count($collection); $i++) {
            for ($j = 0; $j < count($collection[0]); $j++) {
                FileData::create([
                    'file_id' => $file_id->id,
                    'column_id' => $column_id[$j]->id,
                    'data' => $collection[$i][$j],
                ]);
            }
        }
    }
}
