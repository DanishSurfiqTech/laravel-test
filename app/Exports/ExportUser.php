<?php

namespace App\Exports;

use App\Models\FileColumn;
use App\Models\FileData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ExportUser extends DefaultValueBinder implements
    WithHeadings,
    FromArray,
    WithCustomValueBinder
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(int $id)
    {
        $this->file_id = $id;
    }

    public function headings(): array
    {
        $column_name = FileColumn::query()
            ->select('column_name')
            ->where('file_id', $this->file_id)
            ->pluck('column_name');
        return $column_name->toArray();
    }

    public function array(): array
    {
        $column_index = FileColumn::select('id')
            ->where('file_id', $this->file_id)
            ->pluck('id')
            ->toArray();
        $data = FileData::select('data')
            ->where('file_id', $this->file_id)
            ->whereBetween('column_id', [
                reset($column_index),
                end($column_index),
            ])
            ->pluck('data')
            ->toArray();
        return [$data];
    }
}
