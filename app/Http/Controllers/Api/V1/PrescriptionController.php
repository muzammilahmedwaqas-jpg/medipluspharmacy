<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use App\Models\Prescription;
use Illuminate\Support\Facades\Storage;

class PrescriptionController extends Controller
{
    public function index()
    {
        return Prescription::latest()->paginate(10);
    }

    public function store(StorePrescriptionRequest $request)
    {
        $path = $request->file('file')->store('prescriptions', 'public');

        $prescription = Prescription::create([
            'customer_id' => $request->customer_id,
            'file_path' => $path,
            'notes' => $request->notes,
        ]);

        return response()->json($prescription, 201);
    }

    public function show(Prescription $prescription)
    {
        return $prescription;
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());

        return $prescription;
    }

    public function destroy(Prescription $prescription)
    {
        Storage::disk('public')->delete($prescription->file_path);

        $prescription->delete();

        return response()->noContent();
    }

    public function file(Prescription $prescription)
    {
        return response()->file(
            Storage::disk('public')->path($prescription->file_path)
        );
    }
}