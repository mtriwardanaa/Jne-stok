<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ReportExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithStrictNullComparison, WithCustomStartCell, WithTitle, WithColumnFormatting, ShouldAutoSize, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $data = [];
    public $head = [];
    public $title;
    public $format;

    public function __construct($data, $head, $title, $format)
    {
    	$this->data = $data;
    	$this->head = $head;
    	$this->title = $title;
    	$this->format = $format;
    }

    public function collection()
    {
        return $this->r_collect($this->data);
    }

    public function r_collect($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->r_collect($value);
                $array[$key] = $value;
            }
        }

        return collect($array);
    }

    public function headings(): array
    {
        return $this->head;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function title(): string
    {
        return $this->title;
    }

    public function columnFormats(): array
    {
        return [
            'H' => '[$Rp] * #,##0;-[$Rp] * #,##0_-;_-[$Rp] * "-"??_-;_-@_-',
            'I' => '[$Rp] * #,##0;-[$Rp] * #,##0_-;_-[$Rp] * "-"??_-;_-@_-',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
