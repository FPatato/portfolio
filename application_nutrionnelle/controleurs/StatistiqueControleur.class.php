<?php
require_once(__DIR__ . '/../DAO/StatistiqueDAO.class.php');
/// <summary>
/// Controleur permettant de recuperer les donnes statistique
/// </summary>

class StatistiqueControleur
{
    //Retourne le nombre de calories consommés
    public static function getCalories()
    {
        return StatistiqueDAO::GetCalories(true);
    }
    //Retourne le nombre de protéines consommés
    public static function getProtein()
    {
        return StatistiqueDAO::GetProtein(true);
    }
    //Retourne le nombre de Carbs consommés
    public static function getCarbs()
    {
        return StatistiqueDAO::GetCarbs(true);
    }
    //Retourne le nombre de Fat consommés
    public static function getFat()
    {
        return StatistiqueDAO::GetFat(true);
    }
    //Fonction pour le graphique linéaire
    public static function LineGraph()
    {
        return StatistiqueDAO::LineGraph();
    }
    //Fonction pour le graphique circulaire
    public static function PieGraph()
    {
        return StatistiqueDAO::PieGraph();
    }
    //Fonction pour le graphique à barre
    public static function BarGraph()
    {
        return StatistiqueDAO::BarGraph();
    }
    // Retourne les statistiques de l'utilisateur pour aujourd'hui
    public static function GetStatsWithDate($date)
    {
        return StatistiqueDAO::GetStatsWithDate($date);
    }
}


?>