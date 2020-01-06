<?php
$csv = array_map('str_getcsv', file('magazine.csv'));
$output = '<ul class="list-g">';

foreach($csv as $value){
    $output .= '<li id="'.$value[1].'_close" class="hidden"><span class="list-g-name">'.$value[0].'</span>';
    $output .='<button class="stores-btn" data-status="close" data-magazin="'.$value[1].'" data-ip="'.$value[2].'">Inchide Baza de Date</button></li>';
}
$output .='</ul>';
echo $output;
?>
