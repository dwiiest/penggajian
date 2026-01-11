<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,hrd');
    }

    public function index(Request $request)
    {
        $query = Position::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $positions = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:0,1',
        ]);

        $data['transport_allowance'] = $data['transport_allowance'] ?? 0;
        $data['meal_allowance'] = $data['meal_allowance'] ?? 0;
        $data['status'] = $data['status'] ?? 1;

        Position::create($data);

        return redirect()->route('admin.positions.index')
                         ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Position $position)
    {
        return view('admin.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:0,1',
        ]);

        $data['transport_allowance'] = $data['transport_allowance'] ?? 0;
        $data['meal_allowance'] = $data['meal_allowance'] ?? 0;

        $position->update($data);

        return redirect()->route('admin.positions.index')
                         ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function toggleStatus(Position $position)
    {
        $position->status = $position->status == 1 ? 0 : 1;
        $position->save();

        return redirect()->back()->with('success', 'Status jabatan berhasil diubah.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('admin.positions.index')
                         ->with('success', 'Jabatan berhasil dihapus.');
    }
}
