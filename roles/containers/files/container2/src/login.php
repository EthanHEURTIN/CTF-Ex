<?php
session_start();

if (isset($_SESSION['dog_id']) || !empty($_SESSION['dog_id'])) {
    header("Location: welcome.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Premium Doghouse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Quicksand', sans-serif;
        }

        .doghouse-wrapper {
            position: relative;
            width: 100%;
            max-width: 500px;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.5));
        }

        .roof {
            width: 100%;
            height: 120px;
            background: #5d4037;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            margin-bottom: -2px;
            border-bottom: 5px solid #4e342e;
        }

        .house-body {
            background: #8d6e63;
            background-image: repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(0,0,0,0.05) 41px);
            padding: 40px;
            border-radius: 0 0 20px 20px;
            border-bottom: 15px solid #5d4037;
            position: relative;
        }

        .entrance-arch {
            background: #efebe9;
            border-radius: 150px 150px 20px 20px;
            padding: 40px 30px;
            box-shadow: inset 0 10px 15px rgba(0,0,0,0.2);
            border: 5px solid #d7ccc8;
        }

        h2 { font-family: 'Fredoka One', cursive; color: #5d4037; }

        .form-control {
            border-radius: 15px;
            border: 2px solid #d7ccc8;
            padding: 12px;
        }

        .btn-enter {
            background: #d84315;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: bold;
            transition: transform 0.2s;
            box-shadow: 0 4px 0 #bf360c;
        }

        .btn-enter:hover {
            transform: translateY(-2px);
            background: #e64a19;
            color: white;
        }

        .bone-icon { width: 50px; margin-bottom: 10px; }
    </style>
</head>

<body>

  <div class="doghouse-wrapper">
      <div class="roof"></div>
      <div class="house-body text-center">
          <div class="entrance-arch">
              <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" class="bone-icon" alt="Bone">
              <h2 class="mb-4">DOGHOUSE</h2>

              <?php if(isset($_GET['fail'])): ?>
                  <div class="alert alert-warning py-1 small">Wrong scent! Try again.</div>
              <?php endif; ?>

              <form action="process.php" method="POST">
                  <div class="mb-3 text-start">
                      <label class="form-label ms-2 fw-bold" style="color:#795548;">Dog Login</label>
                      <input type="email" name="user_email" class="form-control" placeholder="yuumi@doghouse.ctf" required>
                  </div>
                  <div class="mb-4 text-start">
                      <label class="form-label ms-2 fw-bold" style="color:#795548;">Secret Bark</label>
                      <input type="password" name="user_password" class="form-control" placeholder="••••••••" required>
                  </div>
                  <button type="submit" class="btn btn-enter w-100 shadow-sm">LOG IN</button>
              </form>
              
              <a href="#" id="show-lost-image" class="d-block mt-3 text-muted small text-decoration-none">
                  Lost your leash?
              </a>
              
          </div>
      </div>
  </div>

  <div class="modal fade" id="lostImageModal" tabindex="-1" aria-labelledby="lostImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="lostImageModalLabel">When I get lost, this photo helps me find my way back...</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="assets/cosmo.jpg" alt="Lost dog tag" class="img-fluid rounded" style="max-height: 70vh;">
          <!--<p class="mt-3 small text-muted">Right-click → Save image as... if you want to keep it!</p>-->
        </div>
        <div class="modal-footer">
          <a href="assets/cosmo.jpg" download="cosmo.jpg" class="btn btn-primary">
            Download
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const link = document.getElementById('show-lost-image');
      if (link) {
          link.addEventListener('click', (e) => {
              e.preventDefault();
              const modalElement = document.getElementById('lostImageModal');
              if (modalElement) {
                  const modal = new bootstrap.Modal(modalElement);
                  modal.show();
              } else {
                  console.error("Modal #lostImageModal introuvable");
              }
          });
      } else {
          console.error("Lien #show-lost-image introuvable");
      }
  });
  </script>

  <!-- Important : Bootstrap JS (manquant dans ton code actuel !) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>