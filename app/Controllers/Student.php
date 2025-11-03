<?php

namespace App\Controllers;

<<<<<<< HEAD
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\AnnouncementModel;
use App\Models\QuizModel;

=======
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
class Student extends BaseController
{
    public function dashboard()
    {
<<<<<<< HEAD
        $userId = session()->get('user_id');

        $enrollments = new EnrollmentModel();
        $courses = new CourseModel();
        $announcements = new AnnouncementModel();
        $quizzes = new QuizModel();

        // Fetch enrolled course IDs
        $enrolled = $enrollments->select('course_id')
            ->where('user_id', $userId)
            ->findAll();
        $courseIds = array_map(fn($e) => (int) $e['course_id'], $enrolled);

        $myCourses = [];
        if (! empty($courseIds)) {
            $myCourses = $courses->whereIn('id', $courseIds)->findAll(5);
        }

        // Latest announcements
        $latestAnnouncements = $announcements->orderBy('created_at', 'DESC')->findAll(5);

        // Simple quiz count (for enrolled lessons)
        $myQuizzesCount = 0;
        if (! empty($courseIds)) {
            // naive count via join path: quizzes -> lessons -> courses
            $db = \Config\Database::connect();
            $builder = $db->table('quizzes q')
                ->join('lessons l', 'l.id = q.lesson_id')
                ->whereIn('l.course_id', $courseIds);
            $myQuizzesCount = $builder->countAllResults();
        }

        return view('student_dashboard', [
            'myCourses' => $myCourses,
            'latestAnnouncements' => $latestAnnouncements,
            'myQuizzesCount' => $myQuizzesCount,
            'enrollmentCount' => count($courseIds),
        ]);
=======
        return view('student_dashboard');
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
    }
}


