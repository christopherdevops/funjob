<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QuizQuestion Entity
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $type
 * @property string $question
 * @property int $complexity
 * @property string $source_url
 * @property string $source_book_page
 * @property string $source_book_title
 *
 * @property \App\Model\Entity\Quiz $quiz
 * @property \App\Model\Entity\QuizAnswer[] $quiz_answers
 */
class QuizQuestion extends Entity
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
        '*' => true,
        'id' => false
    ];


    /**
     * Converte la domanda in minuscolo eccetto la prima lettera della domanda
     * Non permette che si possano utilizzare più di due maiuscole consegutive
     *
     * @param str
     */
    protected function _setQuestion($value)
    {
        if (!empty($value)) {
            // ciao sono Mirko... TU?  --> Ciao sono Mirko... Tu?
            // IO SONO MIRKO       -> Io sono mirko
            $string          = $value;
            $beforeUppercase = false;

            for($i = 0; $i < strlen($string); $i++) {
                $letter      = $string[$i];
                $isUppercase = preg_match('/[A-Z]/', $letter);

                if ($beforeUppercase && $isUppercase) {
                    $string[$i] = strtolower($letter);
                }

                $beforeUppercase = $isUppercase;
            }

            return ucfirst($string);
        }

        return null;
    }

    protected function _getQuestion()
    {
        if (!empty($this->_properties['question'])) {
            return strip_tags(ucfirst($this->_properties['question']));
        }

        return null;
    }
}
