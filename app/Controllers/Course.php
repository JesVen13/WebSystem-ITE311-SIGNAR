<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Course extends BaseController
{
    public function enroll()
    {
        helper('security');

        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        if (! $userId || $role !== 'student') {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Unauthorized.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $courseIdRaw = $this->request->getPost('course_id');
        if (! is_numeric($courseIdRaw)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Invalid course_id.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $courseId = (int) $courseIdRaw;
        if ($courseId <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Invalid course_id.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $courseModel = new CourseModel();
        $course = $courseModel->find($courseId);
        if (! $course) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Course not found.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $enrollmentModel = new EnrollmentModel();
        if ($enrollmentModel->isAlreadyEnrolled((int) $userId, $courseId)) {
            return $this->response->setStatusCode(409)->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        $enrollmentId = $enrollmentModel->enrollUser([
            'user_id' => (int) $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        if (! $enrollmentId) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to enroll. Please try again.',
                'csrfHash' => csrf_hash(),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Successfully enrolled in the course!',
            'course' => $course,
            'csrfHash' => csrf_hash(),
        ]);
    }
}
