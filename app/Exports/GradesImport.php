<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class GradesImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    protected $params;
    protected $results = [
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => 0
    ];

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $row
     * @return \App\Models\Grade|null
     */
    public function model(array $row)
    {
        // Vérifier que toutes les colonnes requises sont présentes
        if (empty($row['student_id']) || empty($row['grade_type']) ||
            !isset($row['score']) || !isset($row['max_score'])) {
            $this->results['skipped']++;
            return null;
        }

        // Rechercher l'élève par ID ou code
        $student = Student::find($row['student_id']);
        if (!$student) {
            // Essayer de chercher par code étudiant ou autre identifiant
            $student = Student::where('student_code', $row['student_id'])->first();
            if (!$student) {
                $this->results['skipped']++;
                return null;
            }
        }

        // Vérifier si une note existante doit être mise à jour
        if ($this->params['update_existing']) {
            $existingGrade = Grade::where([
                'student_id' => $student->id,
                'course_id' => $this->params['course_id'],
                'academic_year_id' => $this->params['academic_year_id'],
                'trimester' => $row['trimester'],
                'grade_type' => $row['grade_type']
            ])->first();

            if ($existingGrade) {
                $existingGrade->score = $row['score'];
                $existingGrade->max_score = $row['max_score'];
                $existingGrade->comment = $row['comment'] ?? $existingGrade->comment;
                $existingGrade->evaluation_date = $row['evaluation_date'] ?? $existingGrade->evaluation_date;
                $existingGrade->save();

                $this->results['updated']++;
                return null; // Pas besoin de créer un nouveau modèle
            }
        }

        // Calculer le pourcentage automatiquement
        $percentage = ($row['score'] / $row['max_score']) * 100;

        // Créer une nouvelle note
        $this->results['imported']++;
        return new Grade([
            'student_id' => $student->id,
            'course_id' => $this->params['course_id'],
            'academic_year_id' => $this->params['academic_year_id'],
            'teacher_id' => $this->params['recorded_by'] ?? $row['teacher_id'] ?? null,
            'trimester' => $row['trimester'],
            'grade_type' => $row['grade_type'],
            'score' => $row['score'],
            'max_score' => $row['max_score'],
            'percentage' => $percentage,
            'comment' => $row['comment'] ?? null,
            'evaluation_date' => $row['evaluation_date'] ?? now(),
            'recorded_by' => $this->params['recorded_by'],
            'recorder_id' => auth()->id()
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required',
            'trimester' => ['required', Rule::in([1, 2, 3])],
            'grade_type' => 'required|string|max:50',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0.01',
            'comment' => 'nullable|string',
            'evaluation_date' => 'nullable|date',
        ];
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        $this->results['errors']++;
        Log::error('Erreur d\'importation de notes: ' . $e->getMessage());
    }

    /**
     * @param \Maatwebsite\Excel\Validators\Failure[] $failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->results['errors']++;
            Log::warning('Échec validation ligne ' . $failure->row() . ': ' . implode(', ', $failure->errors()));
        }
    }


    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
