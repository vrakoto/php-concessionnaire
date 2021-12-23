<?php

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