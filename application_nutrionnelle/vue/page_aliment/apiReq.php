<?php
ob_start();
include ("itemNutrition.php");
include ("aliment.php");
$query = urlencode(implode(" ", $_GET['foodenter']));
$url='https://api.calorieninjas.com/v1/nutrition?query='. $query;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER,
[
    'X-Api-Key: TDNZYhxaGcvFVyCpv3wruA==7t0zg7grP0lOKrl7'
]);
$answer = curl_exec($ch);
curl_close($ch);

$data = json_decode($answer,true);


$totalCalories;
$totalCarbs;
$totalFat;
$totalProtein;

if (isset($data['items']) && !empty($data['items'])) 
{
    foreach ($data['items'] as $item)
    {

        $totalCalories += $item['calories'];
        $totalCarbs += $item['carbohydrates_total_g'];
        $totalFat +=$item['fat_total_g'];
        $totalProtein += $item['protein_g'];
        
       
    }
$itemInfo = new itemNutrition
(
    
    implode(" , ", array_column($data['items'], 'name')),
    $totalCalories,
    $data['items'][0]['serving_size_g'],
    $totalFat,
    $data['items'][0]['fat_saturated_g'],
    $totalProtein,
    $data['items'][0]['sodium_mg'],
    $data['items'][0]['potassium_mg'],
    $data['items'][0]['cholesterol_mg'],
    $totalCarbs,
    $data['items'][0]['fiber_g'],
    $data['items'][0]['sugar_g']
);
}

$serializedItemInfo = urlencode(serialize($itemInfo));

header("Location: aliment.php?item=" . $serializedItemInfo);


ob_end_flush();
exit;
?>