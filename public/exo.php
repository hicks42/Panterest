<?php
// – La suite coûte 9 pièces d’or, et me rend 100% de mes PV manquants, +1 force, +1 défense
// – La chambre double coûte 5 pièces d’or et me rend 90% de mes PV manquants, +1 défense
// – La chambre simple coûte 3 pièces d’or et me rend 85% de mes PV manquants

$piecesDOr = rand(5, 15);
$pointsDeVie = rand(50, 100);
$suite=[$gold=>(-9), $lifePoint=>$pointsDeVie,$strength=> (+ 1), $defense=>(+1)];
$double=[$gold=>(-5), $lifePoint=>(+90),$strength=> (+ 1), $defense=>(+1)];
$single=[];

if ($chambre)
