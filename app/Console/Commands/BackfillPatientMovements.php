<?php

namespace App\Console\Commands;

use App\Models\Admission;
use App\Models\PatientMovement;
use Illuminate\Console\Command;

class BackfillPatientMovements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admissions:backfill-movements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create PatientMovement records for existing admissions that are missing them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admissionsWithoutMovement = Admission::with('bed.room')
            ->whereDoesntHave('patientMovements')
            ->whereNotNull('bed_id')
            ->get();

        if ($admissionsWithoutMovement->isEmpty()) {
            $this->info('All admissions already have patient movements.');
            return 0;
        }

        $this->info("Found {$admissionsWithoutMovement->count()} admission(s) without patient movements.");

        $bar = $this->output->createProgressBar($admissionsWithoutMovement->count());
        $bar->start();

        $created = 0;
        $errors = 0;

        foreach ($admissionsWithoutMovement as $admission) {
            try {
                if (!$admission->bed || !$admission->bed->room) {
                    $this->warn("\nSkipping admission {$admission->admission_number}: No bed or room assigned.");
                    $errors++;
                    continue;
                }

                PatientMovement::create([
                    'admission_id' => $admission->id,
                    'room_id' => $admission->bed->room_id,
                    'room_price' => $admission->bed->room->price_per_night,
                    'bed_id' => $admission->bed_id,
                    'started_at' => $admission->admission_date,
                    'ended_at' => $admission->discharge_date,
                ]);

                $created++;
            } catch (\Exception $e) {
                $this->error("\nError for admission {$admission->admission_number}: {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Created {$created} patient movement(s).");
        if ($errors > 0) {
            $this->warn("Encountered {$errors} error(s).");
        }

        return 0;
    }
}
