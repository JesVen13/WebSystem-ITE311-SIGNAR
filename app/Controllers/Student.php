<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\AnnouncementModel;
use App\Models\QuizModel;

class Student extends BaseController
{
    public function dashboard()
    {
        return view('student_dashboard');
    }
}


