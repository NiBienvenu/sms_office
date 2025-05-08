<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicYear::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('year', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated results
        $academicYears = $query->orderBy('year', 'desc')
                              ->paginate(10)
                              ->withQueryString();

        return view('academicYear.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYear = null;
        return view('academicYear.create', compact('academicYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'unique:academic_years,year'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,inactive'],
            'current' => ['boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            // If this year is set as current, update all other years
            if (!empty($validated['current'])) {
                AcademicYear::where('current', true)
                           ->update(['current' => false]);
            }

            AcademicYear::create($validated);
        });

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    public function show(AcademicYear $academicYear)
    {
        // Eager load relationships for statistics
        $academicYear->load(['courses', 'students']);

        return view('academicYear.show', compact('academicYear'));
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academicYear.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'unique:academic_years,year,' . $academicYear->id],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,inactive'],
            'current' => ['boolean'],
        ]);

        DB::transaction(function () use ($validated, $academicYear) {
            // Handle current status changes
            if (!empty($validated['current'])) {
                // If this year is being set as current, update all other years
                AcademicYear::where('id', '!=', $academicYear->id)
                           ->where('current', true)
                           ->update(['current' => false]);
            }

            $academicYear->update($validated);
        });

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        // Check if the academic year has any related records
        if ($academicYear->courses()->exists() ||
            $academicYear->courseEnrollments()->exists() ||
            $academicYear->payments()->exists() ||
            $academicYear->reports()->exists()) {
            return back()->with('error',
                'Cannot delete this academic year because it has related records.');
        }

        if ($academicYear->current) {
            return back()->with('error',
                'Cannot delete the current academic year.');
        }

        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year deleted successfully.');
    }

    // Additional helper methods

    public function toggleStatus(AcademicYear $academicYear)
    {
        $newStatus = $academicYear->status === 'active' ? 'inactive' : 'active';

        $academicYear->update(['status' => $newStatus]);

        return back()->with('success',
            'Academic year status updated successfully.');
    }

    public function setCurrent(AcademicYear $academicYear)
    {
        DB::transaction(function () use ($academicYear) {
            // Remove current status from all other years
            AcademicYear::where('current', true)
                       ->update(['current' => false]);

            // Set this year as current
            $academicYear->update(['current' => true]);
        });

        return back()->with('success',
            'Current academic year updated successfully.');
    }
}
