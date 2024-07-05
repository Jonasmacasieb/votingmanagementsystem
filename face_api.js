<script>
  async function loadModels() {
    // Load the models from the web
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
  }

  document.getElementById('startCamera').addEventListener('click', async function() {
    var video = document.getElementById('cameraFeed');
    // Show the video element
    video.style.display = 'block';

    // Load models
    await loadModels();

    // Start the camera
    if (navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
          video.srcObject = stream;
          video.onloadedmetadata = () => {
            video.play();
            detectFace(video);
          }
        })
        .catch(function(error) {
          console.log("Something went wrong when accessing the camera.");
        });
    }
  });

  async function detectFace(video) {
    const canvas = faceapi.createCanvasFromMedia(video);
    document.body.append(canvas);
    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);
    setInterval(async () => {
      const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      faceapi.draw.drawDetections(canvas, resizedDetections);
      faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
    }, 100);
  }
</script>