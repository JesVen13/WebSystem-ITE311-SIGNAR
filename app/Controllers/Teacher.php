<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\SubmissionModel;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $userId = session()->get('user_id');

        $courses = new CourseModel();
        $lessons = new LessonModel();
        $submissions = new SubmissionModel();

        // Courses instructed by this teacher
        $myCourses = $courses->where('instructor_id', $userId)->findAll();
        $courseIds = array_map(fn($c) => (int) $c['id'], $myCourses);

        // Lessons across teacher's courses
        $lessonsCount = 0;
        if (! empty($courseIds)) {
            $lessonsCount = $lessons->whereIn('course_id', $courseIds)->countAllResults();
        }

        // Pending submissions (simple count where is_correct is NULL or false)
        $pendingSubmissions = 0;
        if (! empty($courseIds)) {
            $db = \Config\Database::connect();
            $builder = $db->table('submissions s')
                ->join('quizzes q', 'q.id = s.quiz_id')
                ->join('lessons l', 'l.id = q.lesson_id')
                ->whereIn('l.course_id', $courseIds)
                ->where('COALESCE(s.is_correct, 0) =', 0);
            $pendingSubmissions = $builder->countAllResults();
        }

        return view('teacher/teacher_dashboard', [
            'myCourses' => $myCourses,
            'courseCount' => count($courseIds),
            'lessonsCount' => $lessonsCount,
            'pendingSubmissions' => $pendingSubmissions,
        ]);
    }

    public function createCourse()
    {
        return view('teacher/create_course', [
            'title' => 'Create Course',
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function storeCourse()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        // Debug: Log session values
        log_message('debug', 'Teacher session - user_id: ' . $userId . ', role: ' . $role);
        
        if (! $userId || $role !== 'teacher') {
            return redirect()->to(base_url('login'));
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[200]',
            'description' => 'permit_empty|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $courses = new CourseModel();

        $ok = $courses->insert([
            'title' => (string) $this->request->getPost('title'),
            'description' => (string) ($this->request->getPost('description') ?? ''),
            'instructor_id' => (int) $userId,
        ]);

        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to create course. Please try again.');
        }

        return redirect()->to(base_url('teacher/dashboard'))->with('success', 'Course created successfully.');
    }
}


