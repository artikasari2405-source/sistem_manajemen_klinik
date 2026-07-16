<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Widget ringkasan
        $todayIncome   = Transaction::where('status', 'Paid')
                            ->whereDate('created_at', Carbon::today())
                            ->sum('total_amount');
        $monthIncome   = Transaction::where('status', 'Paid')
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->sum('total_amount');
        $todayVisits   = Visit::whereDate('visit_date', Carbon::today())->count();
        $monthVisits   = Visit::whereMonth('visit_date', Carbon::now()->month)
                            ->whereYear('visit_date', Carbon::now()->year)
                            ->count();

        // Filter rentang tanggal
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(29)->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('medicalRecord.visit.patient', 'medicalRecord.visit.doctor')
            ->where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filteredTotal = $transactions->sum('total_amount');

        // Data grafik: pendapatan per hari dalam rentang
        $chartData  = [];
        $chartLabels = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $chartLabels[] = $current->format('d M');
            $chartData[]   = Transaction::where('status', 'Paid')
                                ->whereDate('created_at', $current->toDateString())
                                ->sum('total_amount');
            $current->addDay();
        }

        return view('report.index', compact(
            'todayIncome', 'monthIncome', 'todayVisits', 'monthVisits',
            'transactions', 'filteredTotal', 'startDate', 'endDate',
            'chartData', 'chartLabels'
        ) + ['title' => 'Laporan Pendapatan']);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(29)->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('medicalRecord.visit.patient', 'medicalRecord.visit.doctor')
            ->where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filteredTotal = $transactions->sum('total_amount');

        $pdf = Pdf::loadView('report.pdf', compact('transactions', 'filteredTotal', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape');

        $filename = 'laporan-pendapatan-' . $startDate->format('Ymd') . '-sd-' . $endDate->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
