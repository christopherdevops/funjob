<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Cache\Cache;

/**
 * HomePopularQuizzes cell
 */
class HomePopularQuizzesCell extends Cell
{
    const MAX_RESULTS = 10;

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
        $this->loadModel('HomepagePopularQuizzes');

        $quizzes = Cache::remember('home_popular_quizzes', function() {
            $q = $this->HomepagePopularQuizzes->find('popular');
            $q->order(['RAND()']);
            $q->limit(self::MAX_RESULTS);
            return $q->all();
        }, 'home_popular_quizzes');

        $this->set(compact('quizzes'));
    }
}
