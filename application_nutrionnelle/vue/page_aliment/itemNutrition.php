<?php
class itemNutrition {
    public $name;
    public $calories;
    public $serving_size_g;
    public $fat_total_g;
    public $fat_saturated_g;
    public $protein_g;
    public $sodium_mg;
    public $potassium_mg;
    public $cholesterol_mg;
    public $carbohydrates_total_g;
    public $fiber_g;
    public $sugar_g;
    public function __construct($name, $calories, $serving_size_g, $fat_total_g, $fat_saturated_g, $protein_g, $sodium_mg, $potassium_mg, $cholesterol_mg, $carbohydrates_total_g, $fiber_g, $sugar_g) {
        $this->name = $name;
        $this->calories = $calories;
        $this->serving_size_g = $serving_size_g;
        $this->fat_total_g = $fat_total_g;
        $this->fat_saturated_g = $fat_saturated_g;
        $this->protein_g = $protein_g;
        $this->sodium_mg = $sodium_mg;
        $this->potassium_mg = $potassium_mg;
        $this->cholesterol_mg = $cholesterol_mg;
        $this->carbohydrates_total_g = $carbohydrates_total_g;
        $this->fiber_g = $fiber_g;
        $this->sugar_g = $sugar_g;
    }
}
?>