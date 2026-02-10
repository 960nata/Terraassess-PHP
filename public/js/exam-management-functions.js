// Exam Management JavaScript Functions
// This file contains the JavaScript functions for exam management

// Global configuration object
window.examConfig = window.examConfig || {};

function createMultipleChoiceExam() {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminCreateMultipleChoice;
    } else {
        alert('Fitur pembuatan ujian spesifik belum tersedia. Gunakan form di bawah.');
    }
}

function createEssayExam() {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminCreateEssay;
    } else {
        alert('Fitur pembuatan ujian spesifik belum tersedia. Gunakan form di bawah.');
    }
}

function createMixedExam() {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminCreateMixed;
    } else if (window.examConfig.isAdmin) {
        alert('Fitur pembuatan ujian spesifik belum tersedia untuk admin. Gunakan form di bawah.');
    } else if (window.examConfig.hasTeacherCreateMixed) {
        window.location.href = window.examConfig.urls.teacherCreateMixed;
    } else {
        alert('Fitur pembuatan ujian spesifik belum tersedia. Gunakan form di bawah.');
    }
}

function viewExam(examId) {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminView + "/" + examId;
    } else if (window.examConfig.isAdmin) {
        alert('Fitur detail ujian belum tersedia untuk admin.');
    } else if (window.examConfig.hasTeacherView) {
        window.location.href = window.examConfig.urls.teacherView + "/" + examId;
    } else {
        alert('Fitur detail ujian belum tersedia.');
    }
}

function viewResults(examId) {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminResults + "/" + examId;
    } else if (window.examConfig.isAdmin) {
        window.location.href = window.examConfig.urls.adminResults + "/" + examId;
    } else if (window.examConfig.hasTeacherResults) {
        window.location.href = window.examConfig.urls.teacherResults + "/" + examId;
    } else {
        alert('Fitur hasil ujian belum tersedia.');
    }
}

function editExam(examId) {
    if (window.examConfig.isSuperAdmin) {
        window.location.href = window.examConfig.urls.superAdminEdit + "/" + examId;
    } else if (window.examConfig.isAdmin) {
        alert('Fitur edit ujian belum tersedia untuk admin.');
    } else if (window.examConfig.hasTeacherEdit) {
        window.location.href = window.examConfig.urls.teacherEdit + "/" + examId;
    } else {
        alert('Fitur edit ujian belum tersedia.');
    }
}

function publishExam(examId) {
    if (confirm('Apakah Anda yakin ingin mempublikasikan ujian ini?')) {
        console.log('Publishing exam:', examId);
    }
}

function deleteExam(examId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini?')) {
        console.log('Deleting exam:', examId);
    }
}
