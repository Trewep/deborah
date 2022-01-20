<?php

namespace Drupal\bakkerijpeeters\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class BakkerijPeetersForm extends FormBase
{
    protected $state;

    public function __construct(StateInterface $state)
    {
        $this->state = $state;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('state')
        );
    }

    public function getFormId()
    {
        return 'bakkerij_peeters_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['firstname'] = [
            '#type' => 'textfield',
            '#title' => 'Voornaam',
            '#required' => true,

        ];
        $form['lastname'] = [
            '#type' => 'textfield',
            '#title' => 'Naam',
            '#required' => true,

        ];
        $form['tel'] = [
            '#type' => 'tel',
            '#title' => 'Telefoonnummer',
            '#required' => false,

        ];
        $form['bread'] = [
            '#type' => 'checkbox',
            '#title' => 'Brood',

        ];
        $form['coffee'] = [
            '#type' => 'checkbox',
            '#title' => 'Koffiekoek',

        ];
        $form['witchbread'] = [
            '#type' => 'select',
            '#title' => 'Brood',
            '#options' => [
                'Wit',
                'Grijs',
                'Waldkorn'
            ],


        ];
        $form['witchcoffee'] = [
            '#type' => 'checkboxes',
            '#title' => ' Soort Koffiekoek',
            '#options' => [
                'Vierkante koffiekoek met chocolade en pudding',
                'Kersen koek',
                'Carré confiture'
            ],

        ];


        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Bestellen',
            '#button_type' => 'primary',
        ];
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $witchbread =  $form_state->getValue('witchbread');
        $witchcoffee_array = $form_state->getValue('witchcoffee');
        $witchcoffee = "";

        if ($witchcoffee_array[0] == 0 && $witchcoffee_array[1] == 1 && $witchcoffee_array[2] == 2) {
            $witchcoffee .=  'Vierkante koffiekoek met chocolade en pudding, Kersen koek, Carré confiture';
        } elseif ($witchcoffee_array[0] == 0 && $witchcoffee_array[1] == 0 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Vierkante koffiekoek met chocolade en pudding';
        } elseif ($witchcoffee_array[0] == 1 && $witchcoffee_array[1] == 0 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Kersen koek';
        } elseif ($witchcoffee_array[0] == 2 && $witchcoffee_array[1] == 0 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Carré confiture';
        } elseif ($witchcoffee_array[0] == 0 && $witchcoffee_array[1] == 1 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Vierkante koffiekoek met chocolade en pudding, Kersen koek';
        } elseif ($witchcoffee_array[0] == 1 && $witchcoffee_array[1] == 2 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Kersen koek, Carré confiture';
        } elseif ($witchcoffee_array[0] == 0 && $witchcoffee_array[1] == 2 && $witchcoffee_array[2] == 0) {
            $witchcoffee .=  'Vierkante koffiekoek met chocolade en pudding, Carré confiture';
        }


        if ($form_state->getValue('bread') == 1 && $form_state->getValue('coffee') == 1) {
            $bakkerijpeetersOrderType = "Brood en Koffiekoek";
            if ($witchbread == 0) {
                $bakkerijpeetersOrder = "Brood: " . "Wit" . "\n Koffiekoek: " . $witchcoffee;
            } elseif ($witchbread == 1) {
                $bakkerijpeetersOrder = "Brood: " . "Grijs" . "\n Koffiekoek: " . $witchcoffee;
            } elseif ($witchbread == 2) {
                $bakkerijpeetersOrder = "Brood: " . "Waldkorn" . "\n koffiekoek: " . $witchcoffee;
            }
        } elseif ($form_state->getValue('bread') == 1) {
            $bakkerijpeetersOrderType = "Brood";
            if ($witchbread == 0) {
                $bakkerijpeetersOrder = "Brood: " . "Wit";
            } elseif ($witchbread == 1) {
                $bakkerijpeetersOrder = "Brood: " . "Grijs";
            } elseif ($witchbread == 2) {
                $bakkerijpeetersOrder = "Brood: " . "Waldkorn";
            }
        } elseif ($form_state->getValue('coffee') == 1) {
            $bakkerijpeetersOrderType = "Koffiekoek";
            $bakkerijpeetersOrder = "Koffiekoek: " . $witchcoffee;
        } else {
            $bakkerijpeetersOrderType = "Geen bestelling";
        }

        //set order to db
        $orderid = \Drupal::database()->insert('bakkerijpeeters_bestellingen')
            ->fields([
                'firstname' => $form_state->getValue('firstname'),
                'lastname' => $form_state->getValue('lastname'),
                'tel' => $form_state->getValue('tel'),
                'type' => $bakkerijpeetersOrderType,
                'items' => $bakkerijpeetersOrder,
                'orderdate' => time()
            ])
            ->execute();

        $this->messenger()
            ->addStatus('De de bestelling is succesvol opgeslagen');
    }
}
