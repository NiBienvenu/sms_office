<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Course;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class GradesTemplateExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    protected $academicYearId;
    protected $courseId;

    /**
     * @param int|null $academicYearId
     * @param int|null $courseId
     */
    public function __construct($academicYearId = null, $courseId = null)
    {
        $this->academicYearId = $academicYearId;
        $this->courseId = $courseId;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $students = collect();

        // Si une année académique est spécifiée, obtenir tous les étudiants de cette année
        if ($this->academicYearId) {
            $students = Student::where('academic_year_id', $this->academicYearId)
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        } else {
            // Sinon, juste créer un exemple vide
            $students = collect([
                (object) ['id' => '', 'last_name' => 'EXEMPLE', 'first_name' => 'Étudiant'],
                (object) ['id' => '', 'last_name' => 'EXEMPLE', 'first_name' => 'Étudiant 2'],
            ]);
        }

        return $students;
    }

    /**
     * @var Student $student
     */
    public function map($student): array
    {
        return [
            $student->id,
            $student->last_name,
            $student->first_name,
            $student->student_code ?? '',
            1, // Trimestre par défaut
            'TJ1', // Type de note par défaut
            '', // Note
            20, // Note maximale par défaut
            date('Y-m-d'), // Date d'évaluation par défaut
            '', // Commentaire
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'student_id',
            'nom_etudiant',
            'prenom_etudiant',
            'code_etudiant',
            'trimester',
            'grade_type',
            'score',
            'max_score',
            'evaluation_date',
            'comment',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Ajouter des instructions au début du fichier
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'MODÈLE D\'IMPORTATION DES NOTES - INSTRUCTIONS');

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Remplissez ce modèle en respectant les formats indiqués. Les champs obligatoires sont marqués d\'un astérisque (*).');

        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Ne modifiez pas les en-têtes des colonnes. Après avoir rempli les données, importez ce fichier.');

        // Ajouter la description des colonnes
        $sheet->setCellValue('A4', 'Colonne');
        $sheet->setCellValue('B4', 'Description');
        $sheet->setCellValue('C4', 'Obligatoire');
        $sheet->setCellValue('D4', 'Format');

        $columnDescriptions = [
            ['student_id', 'ID ou code de l\'étudiant', 'Oui', 'Numérique ou texte'],
            ['nom_etudiant', 'Nom de l\'étudiant (ne pas modifier)', 'Non', 'Texte'],
            ['prenom_etudiant', 'Prénom de l\'étudiant (ne pas modifier)', 'Non', 'Texte'],
            ['code_etudiant', 'Code étudiant (ne pas modifier)', 'Non', 'Texte'],
            ['trimester', 'Trimestre', 'Oui', '1, 2 ou 3'],
            ['grade_type', 'Type de note', 'Oui', 'Texte (ex: TJ1, Examen, Interrogation)'],
            ['score', 'Note obtenue', 'Oui', 'Numérique'],
            ['max_score', 'Note maximale possible', 'Oui', 'Numérique (ex: 20)'],
            ['evaluation_date', 'Date d\'évaluation', 'Non', 'AAAA-MM-JJ'],
            ['comment', 'Commentaire', 'Non', 'Texte'],
        ];

        $row = 5;
        foreach ($columnDescriptions as $desc) {
            $sheet->setCellValue('A' . $row, $desc[0]);
            $sheet->setCellValue('B' . $row, $desc[1]);
            $sheet->setCellValue('C' . $row, $desc[2]);
            $sheet->setCellValue('D' . $row, $desc[3]);
            $row++;
        }

        // Ajouter des styles
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['italic' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D9E1F2']],
            ],
            3 => [
                'font' => ['italic' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D9E1F2']],
            ],
            4 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'A5A5A5']],
            ],
            'A5:D14' => [
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F2F2F2']],
            ],
            'A16:J16' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A17:J100' => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Modèle d\'importation';
    }
}
