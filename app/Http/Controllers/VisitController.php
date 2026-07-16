<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with(['patient', 'doctor'])->orderBy('visit_date', 'desc')->orderBy('queue_number', 'asc');

        if (Auth::user()->role == 'Dokter') {
            $query->where('doctor_id', Auth::id());
        }

        // Optional date filter
        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->date);
        } else {
            $query->whereDate('visit_date', Carbon::today());
        }

        return view('visit.index', [
            'title' => 'Monitor Antrian',
            'visits' => $query->get(),
        ]);
    }

    public function create()
    {
        return view('visit.create', [
            'title' => 'Daftar Antrian',
            'patients' => Patient::orderBy('name')->get(),
            'doctors' => User::where('role', 'Dokter')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
        ]);

        $visitDate = Carbon::today();

        // Cek jika pasien sudah daftar ke dokter yang sama hari ini
        $exists = Visit::where('patient_id', $validate['patient_id'])
            ->where('doctor_id', $validate['doctor_id'])
            ->whereDate('visit_date', $visitDate)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Pasien sudah terdaftar pada dokter ini untuk hari ini.')->withInput();
        }

        $maxQueue = Visit::where('doctor_id', $validate['doctor_id'])
            ->whereDate('visit_date', $visitDate)
            ->max('queue_number');

        $validate['visit_date'] = $visitDate;
        $validate['queue_number'] = $maxQueue ? $maxQueue + 1 : 1;
        $validate['status'] = 'Waiting';

        Visit::create($validate);
        return to_route('visit.index')->withSuccess('Antrian berhasil didaftarkan. Nomor Antrian: ' . $validate['queue_number']);
    }

    public function status(Request $request, Visit $visit)
    {
        $request->validate([
            'status' => 'required|in:Waiting,Examining,Done,Cancelled'
        ]);

        $visit->update(['status' => $request->status]);
        return back()->withSuccess('Status antrian berhasil diperbarui');
    }
    
    public function destroy(Visit $visit)
    {
        $visit->delete();
        return back()->withSuccess('Antrian berhasil dihapus');
    }
}
