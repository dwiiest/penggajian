<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function generate($employee_id)
    {
        $employee = Employee::with('position')->findOrFail($employee_id);
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $baseSalary = $employee->position->base_salary;
        $dailyTransport = $employee->position->transport_allowance;
        $dailyMeal = $employee->position->meal_allowance;

        $totalHadir = $employee->attendances()
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('status', 'hadir')
            ->count();

        $totalTransport = $dailyTransport * $totalHadir; 
        $totalMeal = $dailyMeal * $totalHadir;
        
        $totalAllowance = $totalTransport + $totalMeal;

        $totalDeduction = 0; 

        $netSalary = ($baseSalary + $totalAllowance) - $totalDeduction;

        Payroll::create([
            'employee_id' => $employee->id,
            'month' => date('F'),
            'year' => $currentYear,
            'basic_salary' => $baseSalary,
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'net_salary' => $netSalary,
            'status' => 'draft',
        ]);

        return redirect()->back()->with('success', 'Gaji berhasil dihitung!');
    }
}