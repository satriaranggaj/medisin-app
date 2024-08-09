<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class VisitController extends Controller
{
    public function store(Request $request)
    {
        $patient = Patient::where('medical_record_number', $request->medical_record_number)->first();

        if ($patient) {
            $patient->visit = ($patient->visit ?? 0) + 1;
            $patient->save();

            return response()->json([
                'message' => 'Kunjungan berhasil disimpan.',
                'data' => $patient
            ], 201);
        } else {
            return response()->json([
                'message' => 'Pasien tidak ditemukan.'
            ], 404);
        }
    }
}

