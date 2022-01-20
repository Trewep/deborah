<?php

namespace Drupal\bakkerijpeeters\Controller;

use Drupal\Core\Controller\ControllerBase;

class OverviewController extends ControllerBase
{
    //Get orders
    public static function getOrders()
    {
        $query = \Drupal::database()->query("SELECT * FROM bakkerijpeeters_bestellingen")->fetchAll(\PDO::FETCH_OBJ);
        $result = $query;

        $data = [];

        foreach ($result as $row) {
            $data[] = [
                'id' => $row->id,
                'firstname' => $row->firstname,
                'lastname' => $row->lastname,
                'tel' => $row->tel,
                'type' => $row->type,
                'items' => $row->items,
                'orderdate' => date("Y-m-d H:i:s", $row->orderdate),
            ];
        }
        $header = array('Bestelnummer', 'Voornaam', 'Achternaam', 'tel', 'type', 'items', 'Besteldatum');
        $build['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $data
        ];
        return [
            $build,
            '#title' => 'bestellingen Bakkerij Peeters'
        ];
    }
}
