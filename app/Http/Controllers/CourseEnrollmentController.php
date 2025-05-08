<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseEnrollmentStoreRequest;
use App\Http\Requests\CourseEnrollmentUpdateRequest;
use App\Models\CourseEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseEnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $courseEnrollments = CourseEnrollment::all();

        return view('courseEnrollment.index', compact('courseEnrollments'));
    }

    public function create(Request $request): View
    {
        return view('courseEnrollment.create');
    }

    public function store(CourseEnrollmentStoreRequest $request): RedirectResponse
    {
        $courseEnrollment = CourseEnrollment::create($request->validated());

        $request->session()->flash('courseEnrollment.id', $courseEnrollment->id);

        return redirect()->route('courseEnrollments.index');
    }

    public function show(Request $request, CourseEnrollment $courseEnrollment): View
    {
        return view('courseEnrollment.show', compact('courseEnrollment'));
    }

    public function edit(Request $request, CourseEnrollment $courseEnrollment): View
    {
        return view('courseEnrollment.edit', compact('courseEnrollment'));
    }

    public function update(CourseEnrollmentUpdateRequest $request, CourseEnrollment $courseEnrollment): RedirectResponse
    {
        $courseEnrollment->update($request->validated());

        $request->session()->flash('courseEnrollment.id', $courseEnrollment->id);

        return redirect()->route('courseEnrollments.index');
    }

    public function destroy(Request $request, CourseEnrollment $courseEnrollment): RedirectResponse
    {
        $courseEnrollment->delete();

        return redirect()->route('courseEnrollments.index');
    }
}
