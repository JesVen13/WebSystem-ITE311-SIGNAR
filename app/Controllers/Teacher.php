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

        return view('teacher_dashboard', [
            'myCourses' => $myCourses,
            'courseCount' => count($courseIds),
            'lessonsCount' => $lessonsCount,
            'pendingSubmissions' => $pendingSubmissions,
        ]);
    }
}


