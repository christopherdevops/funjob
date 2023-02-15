<?php
namespace App\Event;

use Cake\Log\Log;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Core\Configure;

use Cake\Datasource\ConnectionManager;

class UserListener implements EventListenerInterface {

    public function implementedEvents(): array {
        return [
            'Auth.afterIdentify' => 'afterAuthIdentity',

            // Dopo aver completato un quiz
            'Controller.User.Game.afterQuizCompleted'    => [
                'priority' => 100,
                'callable' => 'onQuizComplete'
            ],
            // Dopo che un utente completa un mio quiz
            'Controller.User.Game.afterUserPlayedMyQuiz' => [
                'priority' => 100,
                'callable' => 'onUserCompleteMyQuiz'
            ],
            // Dopo che un utente richiede richiede un oggetto sullo store
            'Controller.User.Store.Purchase'             => [
                'priority' => 100,
                'callable' => 'onStorePurchase'
            ],

            'Controller.User.Settings.Updated' => [
                'priority' => 100,
                'callable' => 'onAccountSettingUpdate'
            ]
        ];
    }

    public function afterAuthIdentity($event)
    {
        $User = $event->getData('0'); // [0 => user_identificato_tramite_auth_component]
        if (!$User) {
            throw new \Exception('Auth.afterIdentify no entity');
        }

        $this->_findOrCreateUserMetaTables($User);
    }

    /**
     * Verifica che l'utente loggato abbia una riga dedicata nelle tue tabelle contenenti
     * le informazioni sull'utente (cambia in base al tipo di utente Users.type)
     *
     * 1. Se è un account aziendale cambia il puntamento alla tabella user_fields --> company_fields
     *
     */
    private function _findOrCreateUserMetaTables($User)
    {
        // 1
        if ($User['type'] == 'company') {
            $this->Users = TableRegistry::get('Companies');
        } else {
            $this->Users = TableRegistry::get('Users');
        }

        $this->Users->AccountInfos->findOrCreate(['user_id' => $User['id']]);
        $this->Users->ProfileBlocks->findOrCreate(['user_id' => $User['id']]);
        $this->Users->Credits->findOrCreate(['user_id' => $User['id']]);
    }


    /**
     * Evento dopo che un utente gioca un quiz
     *
     * Assegna crediti all'utente se ha risposto minimo a N domande correttamente
     * in base anche a quante pubblicità ha visualizzato (riferimento a contatore di Quiz.n._advViewed)
     *
     * Quiz passato: Nel caso ho visualizzato 10 pubblicità, ma ho risposto solo a 9 domande corrette, ricevo 9 PIX
     * Quiz fallito: Non assegnare nessun PIX all'utente
     *
     * @param  \Cake\Event $event
     * @param  [type] $order [description]
     */
    public function onQuizComplete($event, $order) {
        $gameSession = $event->getData('session');
        $Author      = $event->getSubject();

        // PIX:
        // 1 pix ogni rispoosta pubblicità visualizzata
        //$pixTotal    = isset($gameSession['_advViewed']) ? (int) $gameSession['_advViewed'] : 0;
        //$pixTotal  = (int) $gameSession['_score']['reply_correct'];
        $pixTotal = $gameSession['pix']['player'];

        if ($pixTotal > 10) {
            throw new \Exception('Hacking!');
        }

        $UserCredit     = $this->_getUserCredits($event);
        $creditPrevious = $UserCredit->total;
        $creditCurrent  = $UserCredit->total;

        if (
            // Non è l'autore del quiz
            $Author->id != $gameSession['quiz']->user_id &&
            // il quiz/livello è superato
            (int) $gameSession['_score']['score'] >= Configure::readOrFail('app.quiz.minScoreRequired')
        ) {
            $creditCurrent  = $UserCredit->total + $pixTotal;

            // Aggiungo credito all'utente
            $UserCredit->total = $creditCurrent;
            $this->UserCredits->saveOrFail($UserCredit);

            Log::info(
                __(
                    'Guadagno crediti: {user} ha completato un quiz. Guadagnando {credits} PIX ({credits_previous} + {credits} = {credits_current})',
                    [
                        'user'             => $Author->id,
                        'credits'          => $pixTotal, // pubblicità visualizzata
                        'credits_previous' => $creditPrevious,
                        'credits_current'  => $creditCurrent
                    ]
                ),
                ['scope' => ['game']]
            );

            Cache::deleteMany([$Author->id], 'user_credits');
        }

        return [
            'credits' => [
                'current'  => $creditCurrent,
                'previous' => $creditPrevious
            ]
        ];
    }


    /**
     * Evento dopo che un utente gioca il quiz di un altro utente
     *
     * Assegna i crediti all'autore del quiz, solamente se è di tipo "default"
     *
     * @param  [type] $event [description]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function onUserCompleteMyQuiz($event, $order)
    {
        $gameSession  = $event->getData('session');
        $Author       = $event->getSubject();

        if (empty($gameSession['_advViewed'])) {
            return;
        }

        // Utente disabilitato: non deve assegnare PIXs
        if ($Author->is_disabled) {
            return;
        }

        $AuthorCredit = $this->_getUserCredits($event);

        $creditPrevious = $AuthorCredit->total;
        $creditCurrent  = $AuthorCredit->total;

        //$pixTotal       = isset($gameSession['_advViewed']) ? (int) $gameSession['_advViewed'] : 0;
        $pixTotal = $gameSession['pix']['author'];

        if ($pixTotal > 10) {
            throw new \Exception('Hacking!');
        }

        // Creatore del quiz
        //if ($gameSession['quiz']->type == 'default') {
            // Dare credito all'utente che ha giocato il quiz
            if ($gameSession['User']['id'] != $Author->id) {
                $AuthorCredit->total += $pixTotal;
                $this->UserCredits->saveOrFail($AuthorCredit);

                Log::info(
                    __(
                        'Guadagno crediti: {author} guadagna {credits} perchè un utente ha giocato il suo quiz ({credits_previous} + {credits} = {credits_current})',
                        [
                            'author'           => $Author->id,
                            'credits'          => $pixTotal,
                            'credits_previous' => $creditPrevious,
                            'credits_current'  => $creditCurrent
                        ]
                    ),
                    ['scope' => ['game']]
                );
                Cache::deleteMany([$Author->id], 'user_credits');
            }
        //}

        return [
            'credits' => [
                'current'  => $creditCurrent,
                'previous' => $creditPrevious
            ]
        ];
    }

    /**
     * Aggiorna crediti utente dopo acquisto
     *
     * @param  [type] $event [description]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function onStorePurchase($event, $order) {
        $amount = (int) $event->getData('Product')->amount;

        $this->UserCredits = TableRegistry::get('UserCredits');
        $this->StoreOrders = TableRegistry::get('StoreOrders');

        // 1. Aggiorno crediti utente
        $credits = $this->_getUserCredits($event);
        $creditAvailable = $credits->total;
        if ($amount > $credits->total) {
            throw new \Cake\Network\Exception\ForbiddenException(
                __('Crediti insufficienti (richiesti: {0}  attuali: {1})',
                $amount,
                $credits->total
            ));
        }

        $credits->total -= $amount;

        // 2. Creo ordine
        $Order = $this->_createOrder($event);

        // 3. Aggiorno quantità in magazzino
        $Product      = $this->StoreOrders->Products->get($event->getData('Product')->id);
        $productQty   = $Product->qty;
        $Product->qty = $Product->qty -1;

        if ($Product->qty < 0) {
            throw new \Cake\Network\Exception\ForbiddenException(__('Quantità non disponibile'));
        }

        // Aggiorno entities
        $connection = ConnectionManager::get('default');
        $connection->begin();
        $this->UserCredits->saveOrFail($credits);
        $this->StoreOrders->saveOrFail($Order);
        $this->StoreOrders->Products->saveOrFail($Product);
        Cache::deleteMany([$event->getSubject()->id], 'user_credits');
        //throw new \RuntimeException('Test exception!!');
        $connection->commit();


        // Loggers
        $logMessage = sprintf(
            'Acquisto: Utente @%s #%d ha convertito %d crediti (dei suoi %d) nell oggetto `%s` #%d',
            $event->getSubject()->username,
            $event->getSubject()->id,

            $amount,
            $creditAvailable,

            $event->getData('Product')->name,
            $event->getData('Product')->id
        );

        Log::info($logMessage, ['scope' => ['store', 'store:purchase']]);
        Log::debug(__('Crediti: current={0} expected={1} -> now={2}', $creditAvailable, $amount, $credits->total), ['scope' => ['store']]);
        Log::debug(__('Ordine : #{0}', $Order->id), ['scope' => ['store', 'store', 'store:purchase']]);
        Log::debug(__('Aggiorno disponibilità prodotto {0} -> {1}', $productQty, $Product->qty), ['scope' => ['store']]);
    }


    public function onAccountSettingUpdate($event, $newSettings)
    {
        $User   = $event->getSubject();
        $data   = $event->getData();
        $result = [];

        // Itera i campi modificati e crea una array che come key ha il campo di sessione e come
        // value il nuovo valore
        // PS: Viene passato come $Event->getResult() al controller che fà l'ovverride dei campi di sessione
        foreach ($data['fields'] as $key => $value) {
            if ($key == 'lang') {
                $result['session']['Auth.User.lang']  = $value;
                $result['session']['Config.language'] = $value;
            }
        }

        $event->setResult($result);
    }

    /**
     * Restituisce crediti utente
     *
     * @return \Cake\Model\Entity\UserCredit
     */
    private function _getUserCredits($event)
    {
        $this->UserCredits = TableRegistry::get('UserCredits');

        $credits = $this->UserCredits->find()->where(['user_id' => $event->getSubject()->id])->first();
        if (!$credits) {
            $credits = $this->UserCredits->newEntity([
                'user_id' => $event->getSubject()->id,
                'total'   => 0
            ]);
            $this->UserCredits->saveOrFail($credits);
        }

        return $credits;
    }

    /**
     * Crea nuovo ordine
     *
     * @param  \Cake\Event\Event $event
     * @return \Cake\Model\Entity\StoreOrder
     */
    private function _createOrder($event)
    {
        return $this->StoreOrders->newEntity([
            'user_id'    => $event->getSubject()->id,
            'product_id' => $event->getData('Product')->id,
            'status'     => 'pending',
            'amount'     => $event->getData('Product')->amount
        ]);
    }

}
