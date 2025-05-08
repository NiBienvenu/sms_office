<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentSearchController extends Controller
{
    public function search(Request $request)
    {
        $matricule = $request->input('matricule');

        $students = Student::with('classRoom')
            ->where('matricule', 'LIKE', "%$matricule%")
            ->get();


            if ($students->isNotEmpty()) {
                return response()->json($students);
            } else {
                return response()->json(['message' => 'Aucun étudiant trouvé'], 404);
            }


        return response()->json(null, 404);
    }
}
