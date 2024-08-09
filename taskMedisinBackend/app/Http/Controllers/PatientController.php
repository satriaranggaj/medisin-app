<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Resources\PatientResource;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return PatientResource::collection($patients);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'regex:/^[^\d]*$/'
                ],
                'id_number' => [
                    'required',
                    'digits:16',
                    'numeric'
                ],
                'address' => [
                    'required',
                    'string',
                    'min:5'
                ],
            ], [
                'name.required' => 'Nama pasien harus diisi.',
                'name.regex' => 'Nama pasien tidak boleh mengandung angka.',
                'id_number.required' => 'NIK pasien harus diisi.',
                'id_number.digits' => 'NIK pasien harus terdiri dari 16 digit.',
                'id_number.numeric' => 'Nomor ID pasien harus berupa angka.',
                'address.required' => 'Alamat pasien harus diisi.',
                'address.min' => 'Alamat pasien harus memiliki minimal 5 karakter.',
            ]);

            $patient = Patient::create([
                'medical_record_number' => 'RM' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'name' => $validatedData['name'],
                'id_number' => $validatedData['id_number'],
                'address' => $validatedData['address'],
            ]);

            return response()->json([
                'message' => 'Data pasien berhasil disimpan.',
                'data' => $patient
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }
    public function show($medical_record_number)
    {
        $patient = Patient::where('medical_record_number', $medical_record_number)->first();

        if ($patient) {
            return new PatientResource($patient);
        }

        return response()->json([
            'message' => 'Pasien tidak ditemukan.'
        ], 404);
    }

}
