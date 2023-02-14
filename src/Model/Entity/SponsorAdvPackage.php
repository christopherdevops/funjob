<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SponsorAdvPackage Entity
 *
 * @property int $id
 * @property string $type
 * @property int $impressions
 * @property string $label
 * @property float $tax_paypal
 * @property float $tax_funjob
 * @property int $tax_iva
 * @property int $price
 */
class SponsorAdvPackage extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*'      => true,
        'id'     => false,
        'amount' => false
    ];

    public function _getAmount()
    {
        $amount     = 0.00;
        $amount     = (float) $this->_properties['price'];

        // Aggiungere 50% guadagno funjob su maximum memories
        if ($this->_properties['type'] == 'banner-quiz') {
            $amount += $this->_properties['tax_funjob'];
        }

        // PAYPAL
        if (!empty($this->_properties['tax_paypal']) && $this->_properties['tax_paypal'] > 0) {
            $amount += $this->_properties['tax_paypal'];
        }

        // IVA
        if (!empty($this->_properties['tax_iva']) && $this->_properties['tax_iva'] > 0) {
            $iva    = (int) $this->_properties['tax_iva'];
            $amount += ceil( ($amount * $iva) / 100 );
        }

        return $amount;
    }

}
