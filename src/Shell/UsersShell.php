<?php
namespace App\Shell;

use Cake\Console\Shell;

/**
 * Users shell command.
 */
class UsersShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->addSubCommand('create', [
            'help' => 'Nuovo utente'
        ]);

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }


    public function create($username = null, $password = null) {
        $type    = 'user';
        $User    = $this->Users->newEntity(compact('username', 'password', 'type'));
        $created = $this->Users->save($User);


        if (!$created) {
            debug($User->errors());
            $this->abort('Impossibile creare utente');
        }

        $this->out('<success>Username creato</success>');
    }
}
