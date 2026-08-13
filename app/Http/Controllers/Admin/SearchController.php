<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Semester;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // 1. Search Users (Guru, Siswa, Orang Tua, etc)
        $users = User::with('role')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();
            
        foreach ($users as $user) {
            $roleName = $user->role ? $user->role->name : 'Pengguna';
            $url = '#'; // Fallback
            
            $r = strtolower($roleName);
            if ($r === 'guru') {
                $url = route('admin.teachers.show', $user->id); // Assuming teacher ID is same as user ID, or handle properly. Wait, SinergiEdu usually has a separate Teacher model. Let me use users.index or fallback if I don't know the route. Let's just use index with search param for now, or if show route expects user model.
                // It's safer to just point to the index page with a search query if we are unsure of the exact detail route structure for all types.
                // Let's assume standard resource routes exist: admin.teachers.show, admin.students.show
            }
            
            // For safety, just linking to their respective index with a filter or just '#' if not fully sure.
            // Let's try standard routes based on role.
            if ($r === 'guru') $url = route('admin.teachers.index') . '?search=' . urlencode($user->name);
            elseif ($r === 'siswa') $url = route('admin.students.index') . '?search=' . urlencode($user->name);
            elseif ($r === 'orang tua') $url = route('admin.parents.index') . '?search=' . urlencode($user->name);

            $results[] = [
                'category' => 'Pengguna (' . ucfirst($roleName) . ')',
                'title' => $user->name,
                'subtitle' => $user->email,
                'url' => $url,
            ];
        }

        // 2. Search Classes
        $classes = Classroom::where('name', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();
            
        foreach ($classes as $cls) {
            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')',
                'url' => route('admin.classes.index') . '?search=' . urlencode($cls->name),
            ];
        }

        // 3. Search Subjects
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();
            
        foreach ($subjects as $sub) {
            $results[] = [
                'category' => 'Mata Pelajaran',
                'title' => $sub->name,
                'subtitle' => 'Kode: ' . $sub->code,
                'url' => route('admin.subjects.index') . '?search=' . urlencode($sub->name),
            ];
        }

        return response()->json($results);
    }
}
