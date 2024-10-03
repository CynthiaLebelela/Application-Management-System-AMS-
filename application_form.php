<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_approval_db";
// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle file uploads
$uploads = ['picture', 'identification_doc', 'progress_report', 'transfer_letter', 'proof_of_residence', 'recommendation_letter'];
$uploaded_files = [];

foreach ($uploads as $field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES[$field]['name']);
        $upload_file = $upload_dir . uniqid() . '-' . $file_name;
        move_uploaded_file($_FILES[$field]['tmp_name'], $upload_file);
        $uploaded_files[$field] = $upload_file;
    }
}

// Prepare and bind
$stmt = $con->prepare("
    INSERT INTO applications (
        fullname, dob, gender, ethnicity, id_num, picture, 
        schoolname, schooladdress, recent_grade, grade_applying_for, subject_stream, 
        school_activity, home_address, city, email_address, emergency_name, 
        emergency_number, relation, guardian_fullname, guardian_relation, 
        guardian_contact, guardian_email, guardian_occupation, reference_name, 
        reference_school, reference_position, reference_contact, reference_email, 
        identification_doc, progress_report, transfer_letter, proof_of_residence, 
        recommendation_letter
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssssssssssssssssssssssssssss",
    $_POST['fullname'],
    $_POST['dob'],
    $_POST['gender'],
    $_POST['ethnicity'],
    $_POST['id_num'],
    $uploaded_files['picture'] ?? null,
    $_POST['schoolname'],
    $_POST['schooladdress'],
    $_POST['recent_grade'],
    $_POST['grade_applying_for'],
    $_POST['subject_stream'],
    $_POST['school_activity'],
    $_POST['home_address'],
    $_POST['city'],
    $_POST['email_address'],
    $_POST['emergency_name'],
    $_POST['emergency_number'],
    $_POST['relation'],
    $_POST['guardian_fullname'],
    $_POST['guardian_relation'],
    $_POST['guardian_contact'],
    $_POST['guardian_email'],
    $_POST['guardian_occupation'],
    $_POST['reference_name'],
    $_POST['reference_school'],
    $_POST['reference_position'],
    $_POST['reference_contact'],
    $_POST['reference_email'],
    $uploaded_files['identification_doc'] ?? null,
    $uploaded_files['progress_report'] ?? null,
    $uploaded_files['transfer_letter'] ?? null,
    $uploaded_files['proof_of_residence'] ?? null,
    $uploaded_files['recommendation_letter'] ?? null
);

if ($stmt->execute()) {
    echo "Application submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$con->close();
?>