<?php
function getAddress()
{

    if (isset($_POST['cep'])) {
        $cep = $_POST['cep'];

        $cep = filterCep($cep);

        if (isCep($cep)) {
            $address = getAddressViaCep($cep);
            if (property_exists($address, 'erro')) {
                $address = addressEmpty();
                $address->cep = 'CEP NÃO ENCONTRADO';
            }
        } else {
            $address = addressEmpty();
            $address->cep = 'CEP INVÁLIDO';
        }
    } else {
        $address = addressEmpty();
    }

    return $address;
}

function addressEmpty()
{
    return (object)[
        'cep' => '',
        'logradouro' => '',
        'bairro' => '',
        'localidade' => '',
        'uf' => ''
    ];
}

function filterCep(String $cep): String
{
    return preg_replace('/[^0-9]/', '', $cep);
    //procura tudo que NAO é 0-9 e substitui por nada, fica so o cep
}

function isCep(String $cep): bool
{
    return preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep); //valida a quant de num do cep
}

function getAddressViaCep(String $cep)
{
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    return json_decode(file_get_contents($url), false); //consumo da api, convertendo arquivo json para um objeto
}
