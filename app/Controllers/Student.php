<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\AnnouncementModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $announcementModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->announcementModel = new AnnouncementModel();
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');
        
        // Get enrolled courses
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
        
        // Get available courses (not enrolled)
        $availableCourses = [];
        if (!empty($enrolledCourseIds)) {
            $availableCourses = $this->courseModel
                ->select('courses.*, COUNT(enrollments.id) as enrollment_count')
                ->join('enrollments', 'enrollments.course_id = courses.id', 'left')
                ->whereNotIn('courses.id', $enrolledCourseIds)
                ->groupBy('courses.id')
                ->findAll();
        } else {
            $availableCourses = $this->courseModel
                ->select('courses.*, COUNT(enrollments.id) as enrollment_count')
                ->join('enrollments', 'enrollments.course_id = courses.id', 'left')
                ->groupBy('courses.id')
                ->findAll();
        }

        // Get latest announcements
        $latestAnnouncements = $this->announcementModel
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        $data = [
            'enrolledCourses' => $enrolledCourses,
            'availableCourses' => $availableCourses,
            'latestAnnouncements' => $latestAnnouncements,
            'title' => 'Student Dashboard'
        ];

        return view('student/student_dashboard', $data);
    }

    public function enroll($courseId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to enroll in courses'
            ]);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course'
            ]);
        }

        // Enroll the student
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->enrollmentModel->enrollUser($enrollmentData)) {
            // Get the enrolled course details for the response
            $course = $this->courseModel->find($courseId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in the course!',
                'course' => $course
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to enroll in the course. Please try again.'
        ]);
    }
}