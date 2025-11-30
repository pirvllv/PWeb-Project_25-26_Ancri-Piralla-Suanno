<!DOCTYPE html>
<html>
    <head>
      <title>Home page</title>
      <link href="css/style.css" rel="stylesheet">
      <link href="css/custom_style.css" rel="stylesheet">
      <link rel="stylesheet" href="css\fontawesome.css">
      <link rel="stylesheet" href="css/templatemo-574-mexant.css">
      <link rel="stylesheet" href="css/owl.css">
      <link rel="stylesheet" href="css/animate.css">
      <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    </head>
    <body>
        <a href="Frontend/area_personale.php">Area personale</a>
        <a href="Frontend/prova.php">Prova</a>
        <div class="meeting-card">
  <div class="header">
    <div class="title">Upcoming<br />Meetings</div>
    <div class="date-selector" id="month-selector">
      <span>September</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="18"
        height="18"
        fill="currentColor"
        viewBox="0 0 16 16"
      >
        <path
          fill-rule="evenodd"
          d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"
        ></path>
      </svg>
    </div>
  </div>

  <div class="calls-info">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="20"
      height="20"
      fill="currentColor"
      viewBox="0 0 16 16"
    >
      <path
        d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"
      ></path>
    </svg>
    <span>3 calls • Thu, 11</span>
  </div>

  <div class="date-nav-and-indicators">
    <div class="date-nav-container">
      <div class="day-item">
        <div class="day-number">8</div>
        <div class="day-name">Mon</div>
      </div>
      <div class="day-item">
        <div class="day-number">9</div>
        <div class="day-name">Tue</div>
      </div>
      <div class="day-item">
        <div class="day-number">10</div>
        <div class="day-name">Wed</div>
      </div>
      <div class="day-item day-active">
        <div class="day-number">11</div>
        <div class="day-name">Thu</div>
      </div>
      <div class="day-item">
        <div class="day-number">12</div>
        <div class="day-name">Fri</div>
      </div>
      <div class="day-item">
        <div class="day-number">13</div>
        <div class="day-name">Sat</div>
      </div>
    </div>

    <div class="indicator-container">
      <div class="indicator-line"></div>
      <div class="indicator-dot"></div>
      <div class="indicator-dot"></div>
      <div class="indicator-dot"></div>
      <div class="indicator-dot indicator-active"></div>
      <div class="indicator-dot"></div>
      <div class="indicator-dot"></div>
    </div>
  </div>
</div>
    </body>
</html>
<style>
.grid {
    display: grid;
    height: 50vh;
    width: 75vw;
    grid-template-columns: 1fr 1fr;

    /* OPTIONAL: show grid lines */
    border: 2px solid black;
}

.grid > div {
    border: 1px solid #555; /* cell borders */
    background: #ddd;
}
</style>