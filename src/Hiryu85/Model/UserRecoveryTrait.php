<?php
namespace Hiryu85\Model;

use Cake\ORM\Query;

trait UserRecoveryTrait {

    public function findByResetToken(Query $q, $settings = []) {
        if (empty($settings['token'])) {
            throw new \Exception(__('{class}::findByResetToken richiede $settings[token]', __CLASS__));
        }

        $q->bind(':token', $settings['token']);
        $q->where([$this->alias() . '.recovery_token = :token']);

        return $q;
    }

    public function findByConfirmationToken(Query $q, $settings = []) {
        if (empty($settings['token'])) {
            throw new \Exception(__('{class}::findByResetToken richiede $settings[token]', __CLASS__));
        }

        $q->bind(':token', $settings['token']);
        $q->where([$this->alias() . '.confirmation_token = :token']);

        return $q;
    }
}
