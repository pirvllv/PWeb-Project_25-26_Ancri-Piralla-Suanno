<!DOCTYPE html>
<html>
    <head>
        <title>Home page</title>
        <link href="css/style.css" rel="stylesheet">
        <link href="css/custom_style.css" rel="stylesheet">
    </head>
    <body>
        <a href="Frontend/area_personale.php">Area personale</a>
        <div class="grid">
            <div style="width: 50%;">A</div>
            <div style="width: 50%;">B</div>
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