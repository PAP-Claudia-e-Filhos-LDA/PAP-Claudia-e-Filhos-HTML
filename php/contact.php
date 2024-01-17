<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Claudia & Filhos</title>
  <link rel="stylesheet" href="../styles/style.css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body class="main-content">
  <main>
    <section class="container contact active" id="contact">
      <div class="contact-container">
        <div class="main-title">
          <h2>Contact <span>Us</span><span class="bg-text">Contact</span></h2>
        </div>
        <div class="contact-content-con">
          <div class="left-contact">
            <h4>Contact Us here</h4>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. In,
              laborum numquam? Quam excepturi perspiciatis quas quasi.
            </p>
            <div class="contact-info">
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>Location</span>
                </div>
                <p>: Portugal, Leiria</p>
              </div>
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-envelope"></i>
                  <span>Email</span>
                </div>
                <p>
                  <span>: Claudia&Filhos@gmail.com</span>
                </p>
              </div>
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-phone"></i>
                  <span>Mobile Number</span>
                </div>
                <p>
                  <span>: 912 345 678</span>
                </p>
              </div>
            </div>
            <div class="contact-icons">
              <div class="contact-icon">
                <a href="www.facebook.com" target="_blank">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-github"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-youtube"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="right-contact">
            <form action="" class="contact-form">
              <div class="input-control i-c-2">
                <input type="text" required placeholder="YOUR NAME" />
                <input type="email" required placeholder="YOUR EMAIL" />
              </div>
              <div class="input-control">
                <input type="text" required placeholder="ENTER SUBJECT" />
              </div>
              <div class="input-control">
                <textarea name="" id="" cols="15" rows="8" placeholder="Message Here..."></textarea>
              </div>
              <div class="submit-btn">
                <a href="#" class="main-btn">
                  <span class="btn-text">Send</span>
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="controls">
    <a href="index.php">
      <div class="control" data-id="home">
        <i class="fas fa-home"></i>
      </div>
    </a>
    <a href="about.php">
      <div class="control" data-id="about">
        <i class="fas fa-book"></i>
      </div>
    </a>
    <a href="catalogo.php">
      <div class="control" data-id="catalogo">
        <i class="fas fa-lemon"></i>
      </div>
    </a>
    <a href="contact.php">
      <div class="control  active-btn" data-id="contact">
        <i class="far fa-envelope-open"></i>
      </div>
    </a>
    <a href="profile.php">
      <div class="control" data-id="home">
        <i class="fas fa-user"></i>
      </div>
    </a>
  </div>
</body>

</html>