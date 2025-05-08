<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = Report::all();

        return view('report.index', compact('reports'));
    }

    public function create(Request $request): View
    {
        return view('report.create');
    }

    public function store(ReportStoreRequest $request): RedirectResponse
    {
        $report = Report::create($request->validated());

        $request->session()->flash('report.id', $report->id);

        return redirect()->route('reports.index');
    }

    public function show(Request $request, Report $report): View
    {
        return view('report.show', compact('report'));
    }

    public function edit(Request $request, Report $report): View
    {
        return view('report.edit', compact('report'));
    }

    public function update(ReportUpdateRequest $request, Report $report): RedirectResponse
    {
        $report->update($request->validated());

        $request->session()->flash('report.id', $report->id);

        return redirect()->route('reports.index');
    }

    public function destroy(Request $request, Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('reports.index');
    }
}
