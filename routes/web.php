<?php

use App\Http\Controllers\BulletinController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentSearchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});



Auth::routes(['verify' => true]);

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth'])->group(function(){

    # internationalisation
    Route::get('locale/{locale}',[LocaleController::class,'setLocale'])->name('locale');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/header', [HomeController::class, 'getHeaderData']);
    Route::post('/notifications/mark-all-read', [HomeController::class,'markAllNotificationsRead']);

    Route::post('/logout', [HomeController::class, 'logout']);
    // route for showing profil
    Route::post('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/settings', [HomeController::class, 'settings'])->name('settings');

    // Dans le fichier routes.php
    Route::get('404', [PagesController::class, 'show404'])->name('404');
    Route::get('blank', [PagesController::class, 'showBlank'])->name('blank');
    Route::get('buttons', [PagesController::class, 'showButtons'])->name('buttons');
    Route::get('cards', [PagesController::class, 'showCards'])->name('cards');
    Route::get('charts', [PagesController::class, 'showCharts'])->name('charts');
    // Route::get('forgot-password', [PagesController::class, 'showForgotPassword'])->name('forgot-password');
    Route::get('home', [PagesController::class, 'showHome'])->name('home');
    Route::get('register', [PagesController::class, 'showRegister'])->name('register');
    Route::get('tables', [PagesController::class, 'showTables'])->name('tables');
    Route::get('utilities-animation', [PagesController::class,'showUtilitiesAnimation'])->name('utilities-animation');
    Route::get('utilities-border', [PagesController::class,'showUtilitiesBorder'])->name('utilities-border');
    Route::get('utilities-color', [PagesController::class ,'showUtilitiesColor'])->name('utilities-color');
    Route::get('utilities-other', [PagesController::class ,'showUtilitiesOther'])->name('utilities-other');


    Route::resource('academic-years', App\Http\Controllers\AcademicYearController::class);

    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::resource('students', App\Http\Controllers\StudentController::class);

    Route::resource('teachers', App\Http\Controllers\TeacherController::class);

    Route::resource('departments', App\Http\Controllers\DepartmentController::class);

    Route::resource('subjects', App\Http\Controllers\SubjectController::class);

    Route::resource('courses', App\Http\Controllers\CourseController::class);

    Route::resource('course-enrollments', App\Http\Controllers\CourseEnrollmentController::class);

    Route::resource('grades', App\Http\Controllers\GradeController::class);

    Route::resource('schedules', App\Http\Controllers\ScheduleController::class);

    Route::resource('payments', App\Http\Controllers\PaymentController::class);

    Route::resource('payment-details', App\Http\Controllers\PaymentDetailController::class);

    Route::resource('reports', App\Http\Controllers\ReportController::class);

    Route::resource('permissions', App\Http\Controllers\PermissionController::class);

    Route::resource('roles', App\Http\Controllers\RoleController::class);


    //Autre routes
    Route::get('/payment/detailform', [App\Http\Controllers\PaymentController::class, 'detailForm'])->name('payment.detailform');

    Route::get('grades/export', [GradeController::class, 'export'])->name('grades.export');
    Route::get('grades/bulk/entry', [GradeController::class, 'bulkEntryForm'])->name('grades.bulk_entry');
    Route::post('grades/bulk/store', [GradeController::class, 'storeBulk'])->name('grades.store_bulk');

    // API pour récupérer les élèves par classe (utilisé pour la saisie groupée)

    Route::prefix('api')->group(function () {
        Route::get('/student/search', [StudentSearchController::class, 'search'])->name('student.search');
        Route::get('/student/byclass', [GradeController::class, 'getStudentsByClass'])->name('student.byclass');

    });

    // Class Room
    Route::resource('class-rooms', App\Http\Controllers\ClassRoomController::class);
    Route::get('class-rooms/export', [App\Http\Controllers\ClassRoomController::class,'exportStudents'])->name('class-rooms.export');
    Route::post('class-rooms/import', [App\Http\Controllers\ClassRoomController::class,'importStudents'])->name('class-rooms.import');

    // PDF Export Routes
    Route::get('/schedules/pdf/daily', [App\Http\Controllers\ScheduleController::class, 'generateDailyPdf'])->name('schedules.pdf.daily');
    Route::get('/schedules/pdf/weekly', [App\Http\Controllers\ScheduleController::class, 'generateWeeklyPdf'])->name('schedules.pdf.weekly');

    // Weekly View Route
    Route::get('/schedule/weekly/view', [App\Http\Controllers\ScheduleController::class, 'weeklyView'])->name('schedules.weekly.view');


    // Routes pour les bulletins
    Route::resource('bulletins', BulletinController::class);

    // Routes spécifiques pour la génération et l'export
    Route::get('bulletins/generate/form', [BulletinController::class, 'generateForm'])->name('bulletins.generate');
    Route::post('bulletins/generate/by-class', [BulletinController::class, 'generateByClass'])->name('bulletins.generate_by_class');
    Route::post('bulletins/publish/selected', [BulletinController::class, 'publishSelected'])->name('bulletins.publish_selected');

    // Export PDF et impression
    Route::get('bulletins/{bulletin}/pdf', [BulletinController::class, 'generatePdf'])->name('bulletins.pdf');
    Route::get('bulletins/{bulletin}/print', [BulletinController::class, 'printBulletin'])->name('bulletins.print');
    Route::get('bulletins/print/all', [BulletinController::class, 'printAll'])->name('bulletins.print_all');

    // Export multiple
    Route::post('bulletins/export/selected', [BulletinController::class, 'exportSelected'])->name('bulletins.export_selected');

    // Commentaires
    Route::post('bulletins/{bulletin}/add-teacher-comment', [BulletinController::class, 'addTeacherComment'])->name('bulletins.add_teacher_comment');
    Route::post('bulletins/{bulletin}/add-principal-comment', [BulletinController::class, 'addPrincipalComment'])->name('bulletins.add_principal_comment');

    // Publication
    Route::post('bulletins/{bulletin}/publish', [BulletinController::class, 'publish'])->name('bulletins.publish');

    // Routes pour les exports et imports les notes

    Route::get('/grade/export', [GradeController::class, 'export'])->name('grade.export');
    Route::get('/grade/import', [GradeController::class, 'showImportForm'])->name('grade.import');
    Route::post('/grade/import', [GradeController::class, 'import'])->name('grade.import');
    Route::get('/grade/template', [GradeController::class, 'generateTemplate'])->name('grade.template');

});
Route::get('/student/search', [App\Http\Controllers\StudentController::class, 'search'])->name('student.search');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';



