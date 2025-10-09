<?php

namespace App\Http\Controllers;

use App\Models\GroupTask;
use App\Models\GroupMember;
use App\Models\GroupEvaluation;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'teacher') {
            $groupTasks = GroupTask::with(['class', 'subject', 'members'])
                ->where('teacher_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $groupTasks = GroupTask::with(['class', 'subject', 'members'])
                ->where('class_id', $user->class_id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('group-tasks.index', compact('groupTasks'));
    }

    public function create()
    {
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('group-tasks.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_members' => 'required|integer|min:2|max:10',
            'min_members' => 'required|integer|min:2|max:10'
        ]);

        GroupTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'teacher_id' => Auth::id(),
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_members' => $request->max_members,
            'min_members' => $request->min_members,
        ]);

        return redirect()->route('group-tasks.index')
            ->with('success', 'Tugas kelompok berhasil dibuat!');
    }

    public function show(GroupTask $groupTask)
    {
        $groupTask->load(['members.student', 'evaluations.evaluator', 'evaluations.evaluated']);
        
        $user = Auth::user();
        $isMember = $groupTask->members()->where('student_id', $user->id)->exists();
        $isLeader = $groupTask->members()->where('student_id', $user->id)->where('is_leader', true)->exists();
        
        return view('group-tasks.show', compact('groupTask', 'isMember', 'isLeader'));
    }

    public function join(GroupTask $groupTask)
    {
        $user = Auth::user();
        
        if (!$groupTask->canJoin()) {
            return back()->with('error', 'Tidak dapat bergabung dengan kelompok ini.');
        }

        if ($groupTask->members()->where('student_id', $user->id)->exists()) {
            return back()->with('error', 'Anda sudah menjadi anggota kelompok ini.');
        }

        $groupTask->members()->create([
            'student_id' => $user->id,
            'joined_at' => now()
        ]);

        return back()->with('success', 'Berhasil bergabung dengan kelompok!');
    }

    public function leave(GroupTask $groupTask)
    {
        $user = Auth::user();
        $member = $groupTask->members()->where('student_id', $user->id)->first();

        if (!$member) {
            return back()->with('error', 'Anda bukan anggota kelompok ini.');
        }

        if ($member->is_leader) {
            return back()->with('error', 'Ketua kelompok tidak dapat keluar. Pilih ketua baru terlebih dahulu.');
        }

        $member->delete();

        return back()->with('success', 'Berhasil keluar dari kelompok!');
    }

    public function selectLeader(Request $request, GroupTask $groupTask)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        $isCurrentLeader = $groupTask->members()
            ->where('student_id', $user->id)
            ->where('is_leader', true)
            ->exists();

        if (!$isCurrentLeader) {
            return back()->with('error', 'Hanya ketua kelompok yang dapat memilih ketua baru.');
        }

        $newLeader = $groupTask->members()
            ->where('student_id', $request->student_id)
            ->first();

        if (!$newLeader) {
            return back()->with('error', 'Siswa bukan anggota kelompok ini.');
        }

        DB::transaction(function () use ($groupTask, $user, $newLeader) {
            // Hapus status ketua dari ketua lama
            $groupTask->members()
                ->where('student_id', $user->id)
                ->update(['is_leader' => false]);

            // Set ketua baru
            $newLeader->update(['is_leader' => true]);
        });

        return back()->with('success', 'Ketua kelompok berhasil dipilih!');
    }

    public function evaluationForm(GroupTask $groupTask)
    {
        $user = Auth::user();
        $isLeader = $groupTask->members()
            ->where('student_id', $user->id)
            ->where('is_leader', true)
            ->exists();

        if (!$isLeader) {
            return back()->with('error', 'Hanya ketua kelompok yang dapat melakukan penilaian.');
        }

        $members = $groupTask->members()
            ->with('student')
            ->where('student_id', '!=', $user->id)
            ->get();

        $existingEvaluations = $groupTask->evaluations()
            ->where('evaluator_id', $user->id)
            ->get()
            ->keyBy('evaluated_id');

        return view('group-tasks.evaluation', compact('groupTask', 'members', 'existingEvaluations'));
    }

    public function submitEvaluation(Request $request, GroupTask $groupTask)
    {
        $user = Auth::user();
        $isLeader = $groupTask->members()
            ->where('student_id', $user->id)
            ->where('is_leader', true)
            ->exists();

        if (!$isLeader) {
            return back()->with('error', 'Hanya ketua kelompok yang dapat melakukan penilaian.');
        }

        $request->validate([
            'evaluations' => 'required|array',
            'evaluations.*.student_id' => 'required|exists:users,id',
            'evaluations.*.rating' => 'required|in:kurang_baik,cukup_baik,baik,sangat_baik',
            'evaluations.*.comment' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($request, $groupTask, $user) {
            foreach ($request->evaluations as $evaluation) {
                $points = GroupEvaluation::getPointsForRating($evaluation['rating']);
                
                GroupEvaluation::updateOrCreate(
                    [
                        'group_task_id' => $groupTask->id,
                        'evaluator_id' => $user->id,
                        'evaluated_id' => $evaluation['student_id']
                    ],
                    [
                        'rating' => $evaluation['rating'],
                        'points' => $points,
                        'comment' => $evaluation['comment'] ?? null
                    ]
                );
            }
        });

        return back()->with('success', 'Penilaian berhasil disimpan!');
    }

    public function results(GroupTask $groupTask)
    {
        $evaluations = $groupTask->evaluations()
            ->with(['evaluator', 'evaluated'])
            ->get()
            ->groupBy('evaluated_id');

        $memberResults = [];
        foreach ($evaluations as $studentId => $studentEvaluations) {
            $totalPoints = $studentEvaluations->sum('points');
            $averagePoints = $studentEvaluations->avg('points');
            $memberResults[] = [
                'student' => $studentEvaluations->first()->evaluated,
                'total_points' => $totalPoints,
                'average_points' => round($averagePoints, 2),
                'evaluations' => $studentEvaluations
            ];
        }

        // Urutkan berdasarkan total poin tertinggi
        usort($memberResults, function ($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });

        return view('group-tasks.results', compact('groupTask', 'memberResults'));
    }
}
