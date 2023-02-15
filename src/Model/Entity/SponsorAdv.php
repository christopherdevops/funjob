<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Hiryu85\Traits\UploadImageTrait;

/**
 * SponsorAdv Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $banner__img
 * @property string $banner__dir
 * @property \Cake\I18n\Time $active_from
 * @property \Cake\I18n\Time $active_to
 * @property int $is_published
 * @property \Cake\I18n\Time $created
 * @property int $filter_for_age
 * @property string $filter_for_sex
 * @property int $filter_for_age__from
 * @property int $filter_for_age__to
 *
 * @property \App\Model\Entity\User $user
 */
class SponsorAdv extends Entity
{
    use UploadImageTrait;

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
        '*' => true,
        'id' => false
    ];

    /**
     * Restituisce il reale percorso dell'immagine pubblicitaria
     * @return str
     */
    protected function _getImageSrc()
    {
        return $this->banner__dir .'/'. $this->banner__img;
    }


    protected function _getImageTrackSrc()
    {
        if (empty($this->id)) {
            throw new \Exception(__('SponsorAdvEntity::image_track_src works only if isNew = false'));
        }

        if (empty($this->_fields['uuid'])) {
            throw new \Exception(__('SponsorAdvEntity::image_track_src require uuid in SELECT'));
        }

        return Router::url(['_name' => 'adv:image', $this->_fields['uuid']]);
    }

    protected function _getHrefTrackSrc()
    {
        if (empty($this->id)) {
            throw new \Exception(__('SponsorAdvEntity::href_track_src works only if isNew = false'));
        }

        if (empty($this->_fields['uuid'])) {
            throw new \Exception(__('SponsorAdvEntity::href_track_src require uuid in SELECT'));
        }

        return Router::url(['_name' => 'adv:track', $this->_fields['uuid']]);
    }

    /**
     * Imposta Casuale con prefisso FUNJOB-ADV-*
     *
     * @param str
     */
    protected function _setBillingCasual($value)
    {
        if (!empty($value)) {
            if (strpos($value, 'FUNJOB-ADV-') === FALSE) {
                $value = strtoupper('FUNJOB-ADV-' . $value);
            }
        }

        return $value;
    }

}
