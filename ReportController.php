<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === "admin") {
            $reports = Report::all();
        } else {
            $reports = Report::where("user_id", $user->id)->get();
        }

        return view("report.index", compact("reports"));
    }

    public function destroy(Report $report)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, "У вас нет прав для удаления этого отчёта.");
        }
        $report->delete();
        return redirect()->back()->with("success", "Заявление успешно удалено.");
    }

    public function store(Request $request, Report $report)
    {
        $data = $request->validate([
            "description" => "required|string|max:255",
            "number" => "required|string|max:255",
            // "year" => "required|integer|min:0|max:9999",
        ]);

        $data["user_id"] = auth()->id();
        $data["status_id"] = 1;
        $report->create($data);
        return redirect()->back();
    }

    public function show(Report $report)
    {
        $user = auth()->user();

        if ($user->role !== "admin" && $report->user_id !== $user->id) {
            abort(403, "У вас нет прав для просмотра этого отчёта.");
        }

        return view("report.show", compact("report"));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            "description" => "required|string|max:255",
        ]);

        $report->update($data);
        return redirect()->back();
    }

    public function updateStatus(Request $request, Report $report)
    {
        if (auth()->user()->role !== "admin") {
            abort(403, "У вас нет прав для изменения статуса.");
        }

        $data = $request->validate([
            "status_id" => "required|exists:statuses,id",
        ]);

        $report->update($data);

        return redirect()
            ->back()
            ->with("success", "Статус отчёта успешно обновлён!");
    }
}
