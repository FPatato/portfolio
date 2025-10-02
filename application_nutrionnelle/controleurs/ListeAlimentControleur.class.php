<?php
require_once __DIR__ . '/../DAO/ListeAlimentDAO.class.php';
class ListeAlimentControleur
{
    // Ajoute une liste d'aliment
    public static function AddListAliment($id_aliment)
    {
        ListeAlimentDAO::AddListAliment($id_aliment);
    }
    // Supprime une liste d'aliment
    public static function DeleteAliment($id_aliment)
    {
        ListeAlimentDAO::DeleteListAliment($id_aliment);
    }
    // Retourne une liste d'aliment
    public static function GetListAliment($date, $name)
    {
        return ListeAlimentDAO::GetListAliment($date, $name);
    }

}
?>