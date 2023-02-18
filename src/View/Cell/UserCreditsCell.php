<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * UserCredits cell
 */
class UserCreditsCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $credits = null;
        $user_id = $this->request->getSession()->read('Auth.User.id');

        if ($user_id) {
            $this->loadModel('UserCredits');

            $credits = Cache::remember($user_id, function() use ($user_id) {
                $entity = $this->UserCredits->find()
                    ->select(['total'])
                    ->where(['user_id' => (int) $user_id])
                    ->enableHydration(false)
                    ->first();

                if ($entity) {
                    return $entity['total'];
                }

                return null;
            }, 'user_credits');
        }

        $this->set(compact('credits'));
    }
}
