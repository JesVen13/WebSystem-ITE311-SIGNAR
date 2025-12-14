<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h2>Available Courses</h2>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Enrolled Courses Section -->
            <?php if (!empty($enrolledCourses)): ?>
                <div class="mb-5">
                    <h4>My Enrolled Courses</h4>
                    <div class="row g-4">
                        <?php foreach ($enrolledCourses as $course): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <?php if (!empty($course['image'])): ?>
                                        <img src="<?= base_url('uploads/courses/' . $course['image']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= esc($course['title']) ?>"
                                             style="height: 180px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                        <p class="card-text text-muted">
                                            <?= character_limiter($course['description'] ?? 'No description available', 100) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-success">Enrolled</span>
                                            <a href="<?= base_url('course/' . $course['course_id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Available Courses Section -->
            <div class="mb-5">
                <h4>Available Courses</h4>
                <?php if (!empty($availableCourses)): ?>
                    <div class="row g-4">
                        <?php foreach ($availableCourses as $course): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <?php if (!empty($course['image'])): ?>
                                        <img src="<?= base_url('uploads/courses/' . $course['image']) ?>" 
                                             class="card-img-top" 
                                             alt="<?= esc($course['title']) ?>"
                                             style="height: 180px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                        <p class="card-text text-muted flex-grow-1">
                                            <?= character_limiter($course['description'] ?? 'No description available', 100) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="text-muted">
                                                <i class="fas fa-user-graduate me-1"></i>
                                                <?= ($course['enrollment_count'] ?? 0) ?> Enrolled
                                            </span>
                                            <form action="<?= base_url('student/enroll/' . $course['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus-circle me-1"></i> Enroll Now
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No courses available at the moment. Please check back later.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .card-img-top {
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
</style>

<?= $this->endSection() ?>