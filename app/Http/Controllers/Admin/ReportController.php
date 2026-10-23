<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('reporter');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'reportable']);
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,investigating,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update($validated);

        return back()->with('success', 'Status report diperbarui.');
    }

    public function resolve(Request $request, Report $report)
    {
        $validated = $request->validate([
            'action'      => 'required|in:warn,suspend,delete,dismiss',
            'admin_notes' => 'required|string|max:1000',
        ]);

        // Ambil objek yang dilaporkan
        $reportable = $report->reportable;

        if ($reportable) {
            match ($validated['action']) {
                'warn' => null,
                'suspend' => $reportable->update(['status' => 'suspended']),
                'delete' => $reportable->delete(),
                'dismiss' => null,
            };
        }

        $report->update([
            'status'      => $validated['action'] === 'dismiss' ? 'dismissed' : 'resolved',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'Report diselesaikan.');
    }
}