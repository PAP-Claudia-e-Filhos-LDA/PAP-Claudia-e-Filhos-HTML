<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claudia & Filhos</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="main-content">
    <main>
      <section class="container about active" id="about">
        <div class="main-title">
          <h2>About <span>Us</span><span class="bg-text">my stats</span></h2>
        </div>
        <div class="about-container">
          <div class="left-about">
            <h4>Information About me</h4>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet
              labore nihil obcaecati consequatur. Debitis error doloremque, vero
              eos vel nemo eius voluptatem dicta tenetur modi. <br />
              <br />
              La musica delectus dolore fugiat exercitationem a, ipsum quidem
              quo enim natus accusamus labore dolores nam. Unde. Lorem ipsum
              dolor sit amet consectetur, adipisicing elit. Harum non
              necessitatibus deleniti eum soluta.
            </p>
            <div class="btn-con">
              <a href="#" class="main-btn">
                <span class="btn-text">Catalogo</span>
                <span class="btn-icon"><i class="fas fa-download"></i></span>
              </a>
            </div>
          </div>
          <div class="right-about">
            <div class="about-item">
              <div class="abt-text">
                <p class="large-text">650+</p>
                <p class="small-text">
                  Projects <br />
                  Completed
                </p>
              </div>
            </div>
            <div class="about-item">
              <div class="abt-text">
                <p class="large-text">10+</p>
                <p class="small-text">
                  Years of <br />
                  experience
                </p>
              </div>
            </div>
            <div class="about-item">
              <div class="abt-text">
                <p class="large-text">300+</p>
                <p class="small-text">
                  Happy <br />
                  Clients
                </p>
              </div>
            </div>
            <div class="about-item">
              <div class="abt-text">
                <p class="large-text">400+</p>
                <p class="small-text">
                  Customer <br />
                  reviews
                </p>
              </div>
            </div>
          </div>
        </div>
        <h4 class="stat-title">My Timeline</h4>
        <div class="timeline">
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2010 - present</p>
            <h5>Web Developer<span> - Vircrosoft</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2008 - 2011</p>
            <h5>Software Engineer<span> - Boogle, Inc.</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2016 - 2017</p>
            <h5>C++ Programmer<span> - Slime Tech</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2009 - 2013</p>
            <h5>Business Degree<span> - Sussex University</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2013 - 2016</p>
            <h5>Computer Science Degree<span> - Brookes University</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
          <div class="timeline-item">
            <div class="tl-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <p class="tl-duration">2017 - present</p>
            <h5>3d Animation<span> - Brighton University</span></h5>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe
              quasi vero fugit.
            </p>
          </div>
        </div>
      </section>
    </main>

    <div class="controls">
     <a href="index.php"><div class="control" data-id="home">
        <i class="fas fa-home"></i>
      </div></a>
      <a href="about.php"><div class="control active-btn" data-id="about">
        <i class="fas fa-user"></i>
      </div></a>
      <a href="catalogo.php"><div class="control" data-id="catalogo">
        <i class="fas fa-book"></i>
      </div></a> 
      <a href="contact.php"><div class="control" data-id="contact">
        <i class="far fa-envelope-open"></i>
      </div></a>
      <a href="profile.php"><div class="control" data-id="profile">
        <i class="fas fa-user"></i>
      </div></a>
      <div class="control" data-id="home">
        <i class="fas fa-shopping-cart"></i>
      </div>
    </div>
    <div class="theme-btn">
      <i class="fas fa-adjust"></i>
    </div>
    <script src="js/app.js"></script>
  </body>
</html>
