<?php
$csv = array_map('str_getcsv', file('magazine.csv'));
$output = '<ul class="list-g">';

foreach($csv as $value){
    $output .= '<li class="stores-item"><span class="list-g-name">'.$value[0].'</span>';
    $output .='<button class="stores-btn" id="'.$value[1].'_open" data-status="open" data-magazin="'.$value[1].'" data-ip="'.$value[2].'">Deschide Baza de Date</button>';
    $output .='</li>';
}
$output .='</ul>';
echo $output;
?>
