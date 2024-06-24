<link rel="stylesheet" href="../CSS/sty.css">
<div class="background-overlay"></div>

<div class="navbar">
    <a href="../baraNavigare/index.html">Acasă</a>
    <a href="../baraNavigare/probleme.html">Probleme</a>
    <a href="../baraNavigare/materiale.html">Materiale</a>
        <div class="button-container">
                <div class="button-text">
                <?php 
                include 'config.php';
                if ($tip_utilizator == 'profesor') { ?>
                    <a href="../baraNavigare/clase_prof.html">Clasele mele</a>
                <?php } else { ?>
                    <a href="../baraNavigare/clase_stud.html">Clasa mea</a>
                <?php } ?>
                </div>
        </div>
    <a href="../home.html" class="profile-link">Deconectare</a>
    <a href="../baraNavigare/profil.html">Profil</a>
</div>

<div class="container-rotativ">
    <img src="../Images/om_rotativ.png" alt="Skater">
</div>
<div class="content"> </div>
