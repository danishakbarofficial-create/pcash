<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffBalancesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Matrix table ka data
        return User::select('name', 'total_received', 'total_spent', 'cash_balance')->get();
    }

    public function headings(): array
    {
        return ["Staff Member", "Total Assigned (+)", "Total Expenses (-)", "Net Balance"];
    }
}