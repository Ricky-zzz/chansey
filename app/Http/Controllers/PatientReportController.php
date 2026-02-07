<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientReportController extends Controller
{
    /**
     * Generate and display patient report as PDF preview
     */
    public function printReport(Admission $id)
    {
        $admission = $id;

        // Get all admissions for this patient, ordered by admission date (most recent first)
        $allAdmissions = Admission::where('patient_id', $admission->patient_id)
            ->with(['bed', 'attendingPhysician.user'])
            ->orderBy('admission_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('patient.print-report', compact('admission', 'allAdmissions'));
        return $pdf->stream('patient-report-' . $admission->patient->patient_id . '.pdf');
    }

    /**
     * Generate and display detailed admission report as PDF preview
     */
    public function admissionReport(Admission $admission)
    {
        $admission->load([
            'patient',
            'bed',
            'station',
            'attendingPhysician.user',
            'admittingClerk',
            'billingInfo'
        ]);

        $pdf = Pdf::loadView('patient.admission-report', compact('admission'));
        return $pdf->stream('admission-report-' . $admission->admission_number . '.pdf');
    }
}
