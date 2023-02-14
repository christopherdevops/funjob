<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

use Cake\Utility\Text;
use Cake\Routing\Router;

use Hashids\Hashids;

class UserField extends Entity
{
    const HASHIDS_SECRET = 'aklsfiou7234i0tio3yufgioyadsuiy67fu89yhsdjklfgksdiugds90g';

    /**
     * Restituisce path per file CV
     *
     * @return str
     */
    protected function _getCvSrc()
    {
        if (empty($this->_properties['cv']) || empty($this->_properties['user_id'])) {
            return null;
        }

        return sprintf('/uploads/user/cv/%d/%s', $this->_properties['user_id'], $this->_properties['cv']);
    }

    /**
     * Restituisce path per file CV
     *
     * @return str
     */
    protected function _getCvUrl()
    {
        if (empty($this->_properties['cv']) || empty($this->_properties['user_id'])) {
            return null;
        }

        return Router::url(['_name' => 'cv:view', 'uuid' => $this->_getCvUuid(), 'user_id' => $this->_properties['user_id']]);
    }

    /**
     * Restituisce filename CV (senza estensione)
     *
     * Utilizzato per ricavare UUID del file (su routing /cv/:uuid/request)
     *
     * @return str
     */
    protected function _getCvUuid()
    {
        return (new Hashids(self::HASHIDS_SECRET, 25))->encode($this->_properties['user_id']);
    }
}
