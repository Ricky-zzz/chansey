<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdmissionBillingInfo;
use App\Models\Admission;
use App\Services\PatientFileService;

class BillingInfoController extends Controller
{
    protected $patientFileService;

    public function __construct(PatientFileService $patientFileService)
    {
        $this->patientFileService = $patientFileService;
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $billingInfos = collect(); 
        
        if ($search) {
            $billingInfos = AdmissionBillingInfo::with(['admission.patient'])
                ->whereHas('admission', function($q) use ($search) {
                    $q->where('status', '!=', 'Discharged')
                      ->where(function($subQ) use ($search) {
                          $subQ->where('admission_number', 'like', "%{$search}%")
                               ->orWhereHas('patient', function($patientQ) use ($search) {
                                   $patientQ->where('last_name', 'like', "%{$search}%")
                                           ->orWhere('first_name', 'like', "%{$search}%");
                               });
                      });
                })
                ->paginate(15);
        }

        return view('accountant.billinginfo.index', compact('billingInfos'));
    }

    public function show(Admission $admission)
    {
        $billingInfo = $admission->billingInfo;
        $files = $this->patientFileService->getFilesForAdmission($admission->id);
        $loaFile = $files->where('document_type', 'Insurance LOA')->first();
        
        return view('accountant.billinginfo.show', compact('admission', 'billingInfo', 'loaFile'));
    }

    public function update(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string',
            'primary_insurance_provider' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'approval_code' => 'nullable|string',
            'guarantor_name' => 'nullable|string',
            'doc_loa' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $admission->billingInfo()->updateOrCreate(
            ['admission_id' => $admission->id],
            $validated
        );

        // Handle Insurance LOA file upload if provided
        if ($request->hasFile('doc_loa')) {
            $this->patientFileService->replaceOrCreate(
                $request->file('doc_loa'),
                $admission->patient_id,
                $admission->id,
                'Insurance LOA',
                'private'
            );
        }

        return redirect()->route('accountant.billinginfo.show', $admission)
            ->with('success', 'Billing information updated successfully.');
    }
}
