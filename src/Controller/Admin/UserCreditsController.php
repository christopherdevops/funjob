<?php
namespace App\Controller\Admin;

use Cake\Cache\Cache;

use App\Controller\AppController;

/**
 * UserCredits Controller
 *
 * @property \App\Model\Table\UserCreditsTable $UserCredits
 *
 * @method \App\Model\Entity\UserCredit[] paginate($object = null, array $settings = [])
 */
class UserCreditsController extends AppController
{

    /**
     * Edit method
     *
     * @param string|null $id User Credit id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['PUT']);

        $userCredit = $this->UserCredits->get($id, [
            'contain' => [
                'Users'
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            // Somma crediti a totale corrente
            $old   = $userCredit->total;
            $total = (int) $this->request->getData('pixs', 0) + $userCredit->total;
            $this->request->data('total', $total);

            $userCredit = $this->UserCredits->patchEntity($userCredit, $this->request->getData(), ['fieldList' => ['total']]);

            //if ($this->UserCredits->saveOrFail($userCredit)) {

            $this->UserCredits->saveOrFail($userCredit);
            try {
                $cacheKey = $userCredit->user_id;
                Cache::delete($cacheKey, 'user_credits');
            } catch(Exception $e) {};

            $this->Flash->success(
                __(
                    'Crediti aggiornati per {username}: {pix}',
                    [
                        'pix'      => $old . ' ⇒ '. $total,
                        'username' => '<strong>'. $userCredit->user->username . '</strong>',
                    ]
                ),
                ['escape' => false]
            );
            return $this->redirect($this->referer());

            //}
        }
    }

}
