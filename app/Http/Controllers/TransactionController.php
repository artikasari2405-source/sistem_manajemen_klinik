<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $unpaid = MedicalRecord::whereDoesntHave('transaction')->with('visit.patient', 'visit.doctor')->get();
        $paid = Transaction::with('medicalRecord.visit.patient')->where('status', 'Paid')->latest()->get();

        return view('transaction.index', [
            'title' => 'Pembayaran & Kasir',
            'unpaid' => $unpaid,
            'paid' => $paid
        ]);
    }

    public function create(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->transaction) {
            return redirect()->route('transaction.index')->with('error', 'Tagihan ini sudah dibayar.');
        }

        $medicalRecord->load('prescriptions.medicine', 'recordTreatments.treatment', 'visit.patient');
        
        $totalMedicine = $medicalRecord->prescriptions->sum('subtotal');
        $totalTreatment = $medicalRecord->recordTreatments->sum('subtotal');
        $adminFee = 15000;
        $totalAmount = $totalMedicine + $totalTreatment + $adminFee;

        return view('transaction.create', [
            'title' => 'Proses Pembayaran',
            'medicalRecord' => $medicalRecord,
            'totalMedicine' => $totalMedicine,
            'totalTreatment' => $totalTreatment,
            'adminFee' => $adminFee,
            'totalAmount' => $totalAmount
        ]);
    }

    public function store(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'total_amount' => 'required|numeric',
        ]);

        if ($request->payment_amount < $request->total_amount) {
            return back()->withInput()->with('error', 'Nominal pembayaran kurang dari total tagihan.');
        }

        Transaction::create([
            'medical_record_id' => $medicalRecord->id,
            'total_amount' => $request->total_amount,
            'payment_amount' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'status' => 'Paid'
        ]);

        return redirect()->route('transaction.index')->with('success', 'Pembayaran berhasil diproses.');
    }
    
    public function print(Transaction $transaction)
    {
        $transaction->load('medicalRecord.visit.patient', 'medicalRecord.prescriptions.medicine', 'medicalRecord.recordTreatments.treatment');
        
        $totalMedicine = $transaction->medicalRecord->prescriptions->sum('subtotal');
        $totalTreatment = $transaction->medicalRecord->recordTreatments->sum('subtotal');
        $adminFee = 15000;
        
        return view('transaction.print', [
            'title' => 'Invoice Pembayaran',
            'transaction' => $transaction,
            'totalMedicine' => $totalMedicine,
            'totalTreatment' => $totalTreatment,
            'adminFee' => $adminFee
        ]);
    }
}
