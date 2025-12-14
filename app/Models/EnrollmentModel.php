<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    /**
     * Enroll a user in a course
     *
     * @param array $data Should contain user_id and course_id
     * @return int|bool Returns the insert ID on success, false on failure
     */
    public function enrollUser(array $data)
    {
        // Check if already enrolled first
        if ($this->isAlreadyEnrolled($data['user_id'], $data['course_id'])) {
            return false;
        }

        // Set enrollment date if not provided
        if (!isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all courses a user is enrolled in
     *
     * @param int $user_id
     * @return array Array of enrolled courses with course details
     */
    public function getUserEnrollments(int $user_id): array
    {
        return $this->select('enrollments.*, courses.title as course_title, 
                            courses.description as course_description')
                   ->join('courses', 'courses.id = enrollments.course_id')
                   ->where('enrollments.user_id', $user_id)
                   ->orderBy('enrollments.enrollment_date', 'DESC')
                   ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course
     *
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public function isAlreadyEnrolled(int $user_id, int $course_id): bool
    {
        return $this->where('user_id', $user_id)
                   ->where('course_id', $course_id)
                   ->countAllResults() > 0;
    }
}