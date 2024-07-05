<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
    $storedImagePath = $meta['profile_image']; // Assuming the stored image path is in the 'profile_image' column
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>User Management</title>
    <style>
        .face {
            position: absolute;
            top: 90px;
        }

        #black-space.gray-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            opacity: 0.5;
            z-index: 9999;
            display: none;
        }

        .modal-dialog {
            overflow-y: initial !important;
        }

        .modal-body {
            height: auto;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@latest/dist/face-api.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <form action="" id="manage-user" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">

            <div class="form-group">
                <label for="captured-image-preview">Captured Image Preview</label>
                <img id="captured-image-preview" src="" alt="captured image" style="width: 100%; display: none;">
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-primary" id="capture-image-btn">Capture Image</button>
            </div>

            <div id="image-capture-modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Capture Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <video id="video" width="100%" autoplay></video>
                            <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="capture-btn">Capture</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" placeholder="Doe John M">
            </div>

            <div class="form-group">
                <label for="username">School ID</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" maxlength="11" oninput="formatSchoolID(this)" placeholder="00-0000-000">
            </div>

            <div class="form-group" id="department-group">
                <label for="department">Department</label>
                <select name="department" id="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="BSIT">College of Information Technology</option>
                    <option value="BSCRIM">College of Criminology</option>
                    <option value="BSA">College of Accountancy</option>
                    <option value="BSHM">College of Hospitality Management</option>
                    <option value="BSTM">College of Tourism Management</option>
                    <option value="BSAIS">College of Accounting Information System</option>
                    <option value="BSMA">College of Management Accountancy</option>
                    <option value="BSBA">College of Business Administration</option>
                    <option value="BSED">College of Elementary Education</option>
                    <option value="BSSD">College of Secondary Education</option>
                    <option value="BSM">College of Midwifery</option>
                    <option value="BSCNCII">College of Caregiving NC II</option>
                </select>
            </div>

            <div class="form-group" id="course-group">
                <label for="course">Course</label>
                <select name="course" id="course" class="form-control" required>
                    <option value="">Select Course</option>
                    <!-- Course options will be dynamically populated based on the selected department -->
                </select>
            </div>

            <div class="form-group">
                <label for="type">User Type</label>
                <select name="type" id="type" class="custom-select">
                    <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Students</option>
                </select>
            </div>
        </form>

        <script>
            $(document).ready(function() {
                $('#capture-image-btn').click(function() {
                    $('#image-capture-modal').modal('show');
                    startVideoStream();
                });

                // Start video stream for image capture
                function startVideoStream() {
                    const video = document.getElementById('video');
                    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                        .then(function(stream) {
                            video.srcObject = stream;
                        })
                        .catch(function(err) {
                            console.log("An error occurred: " + err);
                        });
                }

                $('#capture-btn').click(async function() {
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    const context = canvas.getContext('2d');

                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        // Draw the video frame to the canvas
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        // Capture the image as a blob
                        canvas.toBlob(async function(blob) {
                            const url = URL.createObjectURL(blob);
                            $('#captured-image-preview').attr('src', url).show();

                            $('#image-capture-modal').modal('hide');
                            const stream = video.srcObject;
                            const tracks = stream.getTracks();
                            tracks.forEach(track => track.stop());

                            // Load face-api models
                            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

                            // Detect face in captured image
                            const capturedImg = await faceapi.bufferToImage(blob);
                            const detections = await faceapi.detectSingleFace(capturedImg, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

                            if (!detections) {
                                alert('No face detected in captured image.');
                                return;
                            }

                            const capturedDescriptor = detections.descriptor;

                            // Fetch and compare with stored image
                            const storedImg = await faceapi.fetchImage('<?php echo $storedImagePath; ?>');
                            const storedDetections = await faceapi.detectSingleFace(storedImg).withFaceLandmarks().withFaceDescriptor();

                            if (!storedDetections) {
                                alert('No face detected in stored image.');
                                return;
                            }

                            const storedDescriptor = storedDetections.descriptor;
                            const distance = faceapi.euclideanDistance(capturedDescriptor, storedDescriptor);

                            if (distance < 0.6) {
                                alert('Face matched! Proceeding to save.');
                            } else {
                                alert('Face not recognized. Please try again.');
                            }
                        }, 'image/png');
                    } else {
                        console.log("The video is not ready.");
                    }
                });

                function formatSchoolID(input) {
                    var schoolID = input.value.replace(/\D/g, '');
                    if (schoolID.length > 2) {
                        schoolID = schoolID.substring(0, 2) + '-' + schoolID.substring(2);
                    }
                    if (schoolID.length > 7) {
                        schoolID = schoolID.substring(0, 7) + '-' + schoolID.substring(7);
                    }
                    input.value = schoolID;
                }

                // Populate course options based on selected department
                $('#department').change(function() {
                    var department = $(this).val();
                    $('#course').empty();
                    switch (department) {
                        case 'BSIT':
                            $('#course').append('<option value="IT">Bachelor of Science in Information Technology</option>');
                            break;
                        case 'BSCRIM':
                            $('#course').append('<option value="Criminology">Bachelor of Science in Criminology</option>');
                            break;
                        case 'BSA':
                            $('#course').append('<option value="Accounting">Bachelor of Science in Accountancy</option>');
                            break;
                        case 'BSHM':
                            $('#course').append('<option value="Hospitality">Bachelor of Science in Hospitality Management</option>');
                            break;
                        case 'BSTM':
                            $('#course').append('<option value="Tourism">Bachelor of Science in Tourism Management</option>');
                            break;
                        case 'BSAIS':
                            $('#course').append('<option value="Accounting">Bachelor of Science in Accounting Information System</option>');
                            break;
                        case 'BSMA':
                            $('#course').append('<option value="Management">Bachelor of Science in Business Administration</option>');
                            break;
                        case 'BSED':
                            $('#course').append('<option value="Education">Bachelor of Science in Elementary Education</option>');
                            break;
                        case 'BSSD':
                            $('#course').append('<option value="Education">Bachelor of Science in Secondary Education</option>');
                            break;
                        case 'BSM':
                            $('#course').append('<option value="Midwifery">Bachelor of Science in Midwifery</option>');
                            break;
                        case 'BSCNCII':
                            $('#course').append('<option value="Caregiving">Bachelor of Science in Caregiving NC II</option>');
                            break;
                        default:
                            $('#course').append('<option value="">Select Course</option>');
                    }
                });

                $('#manage-user').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    if (window.formData) {
                        formData = window.formData;
                        $(this).find('input, select').each(function() {
                            if (this.type !== 'file') {
                                formData.append(this.name, $(this).val());
                            }
                        });
                    }
                    $.ajax({
                        url: 'ajax.php?action=save_user',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 1) {
                                alert('Data successfully saved');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX error: ' + error);
                        }
                    });
                });
            });
        </script>
    </div>
</body>

</html>
