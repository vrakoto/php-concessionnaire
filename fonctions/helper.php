<?php

function includeCSS(string $nomFichier): string
{
    return <<<HTML
    <link rel="stylesheet" href="elements/CSS/$nomFichier.css">
HTML;
}

function form_item(string $id, string $titre, string $typeInput, string $varPHP, string $placeholder = NULL, bool $keepValueOnSubmit = FALSE): string
{
    $value = '';
    if (isset($_POST[$varPHP]) && $keepValueOnSubmit === TRUE) {
        $value = $_POST[$varPHP];
    }
    return <<<HTML
    <div class="mb-4">
        <label for="$id" class="form-label">$titre</label>
        <input type="$typeInput" name="$varPHP" class="form-control" id="$id" placeholder="$placeholder" value="$value">
    </div>
HTML;
}

function nav_item(string $lien, string $titre, string $recognizeTitle = NULL): string
{
    $active = "";
    $titreInVar = strtolower($titre);
    if ($recognizeTitle !== NULL) {
        $titreInVar = strtolower($recognizeTitle);
    }

    if (preg_match("/\b$titreInVar\b/", $_SERVER['REQUEST_URI'])) {
        $active = "active";
    }
    return <<<HTML
     <li class="nav-item">
        <a class="nav-link $active" href="$lien">$titre</a>
    </li>
HTML;
}

function typeParcourir(string $type, string $icon, string $titre, int $nbVehic)
{
    $text = "";
    if (strpos($_SERVER['QUERY_STRING'], $type)) {
        $text = "text-primary";
    }

    return <<<HTML
    <a href="index.php?action=parcourir&type=$type" class="$text btn border" ><i class="fas fa-$icon"></i> $titre ($nbVehic)</a>
HTML;
}

function valOptionSub(string $varPHP, string $valeur): string
{
    $s = "";
    if (isset($_REQUEST[$varPHP]) && $_REQUEST[$varPHP] === $valeur) {
        $s = "selected";
    }
    return $s;
}