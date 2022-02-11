<?php
#fontion qui calculer le PGCD.
#INPUT: a, b deux nombre. 
#OUTPUT: le plus grand diviseur commun.
function pgcd($a, $b)
{
    $rest = $a % $b;       
    $a = $b;
    $b = $rest;
    if ($b == 0) 
        return $a;
    else 
        return pgcd($a, $b);
}

# fonction de algorithme d'Euclide etendu.
#INPUT: a, b deux nombre. 
#OUTPUT: [u, v, d] u, v: les coefficients de BachetBezout, d : le plus grand diviseur commun.
function EEA($a, $b)
{
    $d = $a; $u = 1;
    if($b == 0)
    {
        $v = 0;
        return [$u, round($v), $d]; 
    }
    else{
        $u1 = 0;
        $d1 = $b;
    }
    while(TRUE){
        if($d1 == 0)
        {
            $v = ($d - $a * $u) / $b;
            return [$u, round($v), $d];
        }
        $q = (int) ($d / $d1);
        $d2 = $d % $d1;
        $u2 = $u - ($q * $u1);
        $u = $u1; $d = $d1;
        $u1 = $u2; $d1 = $d2;
    }
}


#fonction qui retourne l'inverse de e, d tel que de = 1 [m].
#INPUT: m, e deux nombre avec m > e. 
#OUTPUT: r l'inverse de e tel que e*r = 1 [m].
function inverseModulaire($m, $e)
{
    $r = EEA($m, $e);
    if ($r[2] != 1)
        return NULL;
    return $r[1];
}

#fonction qui verefier si un nombre est premier ou non.
#INPUT: n un nombre. 
#OUTPUT: TRUE si n premier, sinon FALSE.
function estPremier($n)
{
    if ($n == 2) 
        return TRUE;
    if (($n % 2) == 0) 
        return FALSE;
    $c = (int)(sqrt($n));
    for ($i = 3; $i <= $c; $i += 2) {
        if (($n % $i) == 0) 
            return FALSE;
    }
    return TRUE;
}

#fonction qui retourne un nombre premier aleatoire dans un domaine donner.
#INPUT: inf ,lg  inf c'est le debut de domaine et lg +inf c'est la fin. 
#OUTPUT: h nombre premier entre [inf, inf + lg].
function premierAleatoire($inf, $lg)
{
    while(TRUE)
    {
        $h = rand($inf, $lg + $inf);
        if(estPremier($h))
            return $h;
    }
}

#fonction qui retourne un nombre qui premier avec un autre nombre donner.
##INPUT: n un nombre. 
#OUTPUT: h c'est nombre aleatoire premier avec n.
function premierAleatoireAvec($n)
{
    while(TRUE)
    {
        $h = rand(2, $n-1);
        if(pgcd($n, $h) == 1)
            return $h;
    }
    
}

#fonction pour choix de cle aleatoire dans un domaine.
#INPUT: inf, lg dexu nombre pour definer un domain [inf, inf + lg]. 
#OUTPUT: [p, q, e] c'est le triple qui represente le cle.
function choixCle($inf, $lg)
{
    $p = premierAleatoire($inf, $lg);
    $q = premierAleatoire($p + 1, $lg);
    $fi = ($p - 1) * ($q - 1);
    $e = premierAleatoireAvec($fi);
    return [$p, $q, $e];
}

#fonction pour cree un cle publique.
#INPUT: p, q, e c'est le triple qui represente le cle. 
#OUTPUT: [n, e] le cle publique.
function clePublique($p, $q, $e)
{
    $n = $p * $q;
    return [$n, $e];
}

#fonction  pour cree un cle privee 
#INPUT: p, q, e c'est le triple qui represente le cle. 
#OUTPUT: [n, e] le cle privee.
function clePrivee($p, $q, $e)
{
    $n = $p * $q;
    $fi = ($p - 1) * ($q - 1);
    $d = inverseModulaire($fi, $e);
    return [$n, $d];
}

#fonction  pour coder un message donner
#INPUT: m c'est le message a coder, cp c'est le cle publique (n, e). 
#OUTPUT: c le message coder.
function codageRSA($m, $cp)
{
    if ($m > $cp[0])
    {
        print('donner message moins que n');
        return;
    }
        

    $c = pow($m, $cp[1]) % $cp[0];
    if ($c < 0)
        $c += $cp[0];
    return $c;
}

#fonction  pour decoder un message donner
#INPUT: c c'est le message a decoder, cpr c'est le cle privee (n, d). 
#OUTPUT: m le message decoder.
function decodageRSA($c, $cpr)
{
    if ($c > $cpr[0])
    {
        print('donner message crypter moins que n');
        return;
    }
    $m = pow($c, $cpr[1]) % $cpr[0];
    if ($m < 0)
        $m += $cpr[0];
    return $m;
}



//test de la fonction inverseModulaire
print(json_encode(inverseModulaire(40, 23))."\n");

//test de la fonction estPremier.
print(estPremier(10)."\n");

//test de la fonction premierAleatoireAvec.
print(premierAleatoireAvec(1000)."\n");

//test de la fonction premierAleatoire.
print(premierAleatoire(1000, 1000000)."\n");

//test de la fonction choixCle.
print(json_encode(choixCle(1, 10))."\n"); 

//test de la fonction clePublique. 
print(json_encode(clePublique(3, 11, 3))."\n");

//test de la fonction clePrivee. 
print(json_encode(clePrivee(3, 11, 3))."\n");

//test de la fonction codageRSA. 
print(codageRSA(4, [33, 3])."\n");

//test de la fonction decodageRSA. 
print(decodageRSA(31, [33, 7])."\n");


