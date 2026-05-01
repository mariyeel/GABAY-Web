<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\UserController;

Route::get('/firebase-store', [FirebaseController::class, 'store']);
Route::get('/firebase-users', [FirebaseController::class, 'index']);
Route::get('/firebase-update/{id}', [FirebaseController::class, 'update']);
Route::get('/firebase-delete/{id}', [FirebaseController::class, 'destroy']);
Route::get('/patients/{patientId}/location', [FirebaseController::class, 'getPatientLocation']);
Route::post('/patients/{patientId}/location', [FirebaseController::class, 'updatePatientLocation']);

Route::get('/', [UserController::class, 'landing'])->name('landing');
Route::get('/signup', [UserController::class, 'create'])->name('signup.create');
Route::post('/signup', [UserController::class, 'store'])->name('signup.store');
Route::get('/login', [UserController::class, 'login'])->name('login.create');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.store');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/caregiver/dashboard', [UserController::class, 'caregiverDashboard'])->name('dashboard.caregiver');
Route::post('/caregiver/connect', [UserController::class, 'connectPatient'])->name('caregiver.connect');
Route::get('/patient/dashboard', [UserController::class, 'patientDashboard'])->name('dashboard.patient');
Route::get('/patient/navigation', [UserController::class, 'patientNavigation'])->name('patient.navigation');
Route::get('/patient/navigation/mapbox/reverse-geocode', [UserController::class, 'mapboxReverseGeocode'])->name('patient.navigation.mapbox.reverse');
Route::get('/patient/navigation/mapbox/search', [UserController::class, 'mapboxSearch'])->name('patient.navigation.mapbox.search');
Route::get('/patient/navigation/mapbox/directions', [UserController::class, 'mapboxDirections'])->name('patient.navigation.mapbox.directions');
Route::get('/patient/history', [UserController::class, 'patientHistory'])->name('patient.history');
Route::post('/patient/navigation/session', [UserController::class, 'startNavigationSession'])->name('patient.navigation.session.start');
Route::patch('/patient/navigation/session/{navigationSession}', [UserController::class, 'completeNavigationSession'])->name('patient.navigation.session.update');
