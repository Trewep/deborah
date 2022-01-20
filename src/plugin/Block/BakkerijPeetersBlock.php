<?php

namespace Drupal\bakkerijpeeters\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides a 'BakkerijPeeters' block.
 *
 * @Block(
 * id= "Bakkerij_peeters_Block",
 * admin_label = "bakkerij peeters block"
 * )
 */
class BakkerijPeetersBlock extends BlockBase
{
    public function build()
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\bakkerijpeeters\Form\BakkerijPeetersForm');
        return [
            $form,
            '#attached' => [
                'library' => ['bakkerijpeeters/bakkerijpeeters']
            ]
        ];
    }
}
