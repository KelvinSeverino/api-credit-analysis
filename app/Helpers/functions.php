<?php
function is_CPF(string $document)
{
    // Extrai os números
    $cpf = preg_replace('/[^0-9]/is', '', $document);

    // Valida tamanho
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{11}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) 
    {
        for ($d = 0, $c = 0; $c < $t; $c++) 
        {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) 
        {
            return false;
        }
    }
    return true;
}