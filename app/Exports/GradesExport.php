<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradesExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting,
    WithTitle
{
    protected $filters;

    /**
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Grade::query()
            ->with(['student', 'course', 'teacher', 'academicYear'])
            ->orderBy('trimester')
            ->orderBy('grade_type');

        // Appliquer les filtres
        if (!empty($this->filters['academic_year_id'])) {
            $query->where('academic_year_id', $this->filters['academic_year_id']);
        }

        if (!empty($this->filters['course_id'])) {
            $query->where('course_id', $this->filters['course_id']);
        }

        if (!empty($this->filters['trimester'])) {
            $query->where('trimester', $this->filters['trimester']);
        }

        if (!empty($this->filters['teacher_id'])) {
            $query->where('teacher_id', $this->filters['teacher_id']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * @var Grade $grade
     */
    public function map($grade): array
    {
        $row = [
            $grade->student->last_name,
            $grade->student->first_name,
            $grade->student->student_code ?? '',
            $grade->course->name,
            'Trimestre ' . $grade->trimester,
            $grade->grade_type,
            $grade->score,
            $grade->max_score,
            $grade->percentage,
            $grade->teacher->last_name . ' ' . $grade->teacher->first_name,
            $grade->student->academicYear->year,
            $grade->evaluation_date ? $grade->evaluation_date->format('Y-m-d') : '',
        ];

        // Ajouter les commentaires si demandé
        if (!empty($this->filters['include_comments'])) {
            $row[] = $grade->comment;
        }

        return $row;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'Nom',
            'Prénom',
            'Code étudiant',
            'Cours',
            'Trimestre',
            'Type',
            'Note',
            'Note max',
            'Pourcentage',
            'Enseignant',
            'Année académique',
            'Date d\'évaluation',
        ];

        // Ajouter l'en-tête de commentaire si demandé
        if (!empty($this->filters['include_comments'])) {
            $headings[] = 'Commentaire';
        }

        return $headings;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_PERCENTAGE_00,
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            // Style pour toutes les cellules
            'A:Z' => [
                'alignment' => ['vertical' => 'center'],
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Notes des élèves';
    }
}
