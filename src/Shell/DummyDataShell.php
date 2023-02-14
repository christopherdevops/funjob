<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

/**
 * DummyData shell command.
 */
class DummyDataShell extends Shell
{

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
        $parser->addOption('total', [
            'default' => 10, 'help' => 'Numero da generare'
        ]);

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }


    /**
     * Crea nuovo utente
     *
     * @return [type] [description]
     */
    public function users()
    {
        $this->Users = TableRegistry::get('Users');
        $skillTags['IT'] = [
            'cakephp', 'programming', 'laravel', 'jquery', 'angularjs', 'css', 'html', 'js', 'webdesigner',
            'c++', 'c', 'arduino', 'sistemista', 'full stack web developer', 'unix', 'linux'
        ];
        $skillTags['avvocato']    = ['avvocato', 'avvocato penalista', 'avvocato civile', 'diritto', 'diritto italiano', 'diritto europeo'];
        $skillTags['traduttore']  = ['traduttore italiano', 'traduttore russo', 'traduttore tedesco', 'traduttore arabo', 'traduttore spagnolo'];
        $skillTags['disegnatore'] = [
            'fumettista', 'rappresentatore', 'disegno tecnico', 'disegno computer', 'photoshop', 'designer interni', 'designer esterni', 'pittore'
        ];

        for ($i=0; $i < $this->params['total']; $i++) {
            $skillClone    = $skillTags;
            $skillCategory = array_rand($skillClone);
            $skillValues   = $skillClone[$skillCategory];
            $skills        = [];

            for ($i=0; $i < rand(1, sizeof($skillValues)); $i++) {
                $skillName    = array_rand($skillValues);
                $skills[] = ['name' => $skillValues[$skillName], 'perc' => rand(5, 100)];
                unset($skillValues[$skillName]);
            }

            $User = $this->Users->newEntity([
                'first_name' => $faker->firstName(),
                'last_name'  => $faker->lastName(),
                'type'       => 'user',
                'password'   => 'password',
                'username'   => $faker->userName(),
                'title'      => $faker->sentence(5, false),
                'profession' => $faker->jobTitle(),
                'email'      => $faker->freeEmail(),


                'account_info' => [
                    'birthday'  => $faker->dateTimeThisCentury->format('Y-m-d'),
                    'public_cv' => 0
                ],

                'user_skills' => $skills
            ], [
                'associated' => [
                    'AccountInfos',
                    'UserSkills'
                ]
            ]);

            $this->Users->save($User);
        }
    }
}
