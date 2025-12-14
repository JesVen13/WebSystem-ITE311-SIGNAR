<!-- app/Views/student/student_dashboard.php -->
<?php helper('text'); ?>
<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2>Student Dashboard</h2>
            
            <!-- Success/Error Messages -->
            <div id="alertContainer"></div>

            <!-- Enrolled Courses -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">My Enrolled Courses</h4>
                </div>
                <div class="card-body">
                    <div class="row" id="enrolledCoursesContainer">
                        <?php if (!empty($enrolledCourses)): ?>
                            <?php foreach ($enrolledCourses as $course): ?>
                                <div class="col-md-4 mb-4" id="course-<?= $course['course_id'] ?>">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= esc($course['course_title']) ?></h5>
                                            <p class="card-text"><?= character_limiter($course['course_description'] ?? 'No description', 100) ?></p>
                                            <span class="badge bg-success">Enrolled</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-muted">You are not enrolled in any courses yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Available Courses -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Available Courses</h4>
                </div>
                <div class="card-body">
                    <div class="row" id="availableCoursesContainer">
                        <?php if (!empty($availableCourses)): ?>
                            <?php foreach ($availableCourses as $course): ?>
                                <div class="col-md-4 mb-4" id="available-course-<?= $course['id'] ?>">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                            <p class="card-text flex-grow-1"><?= character_limiter($course['description'] ?? 'No description', 100) ?></p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="text-muted">
                                                    <i class="fas fa-users"></i> 
                                                    <?= $course['enrollment_count'] ?? 0 ?> enrolled
                                                </span>
                                                <button class="btn btn-primary btn-sm enroll-btn" 
                                                        data-course-id="<?= $course['id'] ?>">
                                                    <i class="fas fa-plus-circle"></i> Enroll
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-muted">No courses available at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrollment Success Template (Hidden) -->
<template id="enrolledCourseTemplate">
    <div class="col-md-4 mb-4" id="course-{course_id}">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{course_title}</h5>
                <p class="card-text">{course_description}</p>
                <span class="badge bg-success">Enrolled</span>
            </div>
        </div>
    </div>
</template>

<!-- Alert Template (Hidden) -->
<template id="alertTemplate">
    <div class="alert alert-dismissible fade show" role="alert">
        {message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let csrfHash = '<?= csrf_hash() ?>';
    const csrfFieldName = '<?= csrf_token() ?>';

    function applyCsrfHeader() {
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': csrfHash
            }
        });
    }

    applyCsrfHeader();

    // Handle enroll button click
    $(document).on('click', '.enroll-btn', function() {
        const button = $(this);
        const courseId = button.data('course-id');
        const courseCard = button.closest('.col-md-4');
        
        // Disable button to prevent multiple clicks
        button.prop('disabled', true);
        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enrolling...');

        const postData = { course_id: courseId };
        postData[csrfFieldName] = csrfHash;

        // Send AJAX request to enroll
        $.post('<?= site_url('course/enroll') ?>', postData)
            .done(function(response) {
                if (response && response.csrfHash) {
                    csrfHash = response.csrfHash;
                    applyCsrfHeader();
                }
                if (response.success) {
                    // Show success message
                    showAlert('Successfully enrolled in ' + response.course.title, 'success');
                    
                    // Remove from available courses
                    courseCard.fadeOut(300, function() {
                        $(this).remove();
                        
                        // If no more available courses, show message
                        if ($('#availableCoursesContainer .col-md-4').length === 0) {
                            $('#availableCoursesContainer').html('<div class="col-12"><p class="text-muted">No courses available at the moment.</p></div>');
                        }
                    });
                    
                    // Add to enrolled courses
                    const template = $('#enrolledCourseTemplate').html()
                        .replace(/{course_id}/g, response.course.id)
                        .replace(/{course_title}/g, response.course.title)
                        .replace(/{course_description}/g, response.course.description || 'No description');
                    
                    $('#enrolledCoursesContainer').prepend(template);
                    
                    // If "no courses" message is shown, remove it
                    if ($('#enrolledCoursesContainer .text-muted').length) {
                        $('#enrolledCoursesContainer .text-muted').parent().remove();
                    }
                } else {
                    showAlert(response.message || 'Failed to enroll in the course', 'danger');
                    button.prop('disabled', false).html('<i class="fas fa-plus-circle"></i> Enroll');
                }
            })
            .fail(function(xhr) {
                const response = xhr.responseJSON || {};
                if (response && response.csrfHash) {
                    csrfHash = response.csrfHash;
                    applyCsrfHeader();
                }
                showAlert(response.message || 'An error occurred. Please try again.', 'danger');
                button.prop('disabled', false).html('<i class="fas fa-plus-circle"></i> Enroll');
            });
    });

    // Function to show alert messages
    function showAlert(message, type = 'info') {
        const alertHtml = $('#alertTemplate').html()
            .replace('{message}', message)
            .replace('alert-dismissible', `alert-${type} alert-dismissible`);
        
        const $alert = $(alertHtml);
        $('#alertContainer').html($alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $alert.alert('close');
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>