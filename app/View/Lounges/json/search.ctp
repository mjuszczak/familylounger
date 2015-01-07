<?php

$formattedResults = array();
foreach($results as $result){

    $patientEmail = false;
    if(isset($results['LoungeUser'][0]))
        $patientEmail = $result['LoungeUser'][0]['User']['email'];

    $formattedResults[] = array(
        'url' => $result['Lounge']['subdomain'] . '.' . SITE_DOMAIN,
        'name' => $result['Lounge']['name'],
        'privacy' => $result['Lounge']['privacy'],
        'img' => $this->Gravatar->url($patientEmail)
    );
}
echo json_encode($formattedResults);

