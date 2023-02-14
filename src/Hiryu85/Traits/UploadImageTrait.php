<?php
namespace Hiryu85\Traits;

Trait UploadImageTrait {

    public function imagePath(\Cake\ORM\Entity $entity) {
        return 'uploads/' . $entity->source() . '/fields/'. $entity->id. '/'. $entity->image;
    }

    /**
     * Inserisce il suffisso del modificatore dell'immagine
     *
     * foo.jpg -> foo--200x200.jpg
     *
     * @param  string $filePath
     * @param  string $modifierSuffix
     * @return str
     */
    public function imageSize($filePath, $modifierSuffix = null)
    {
        if (!$modifierSuffix) {
            return $filePath;
        }

        // Se è un filepath generato tramite holder.js cambia le dimensioni tramite preg_replace
        if (strstr($filePath, 'holder.js/')) {
            return preg_replace('/^holder.js\/([0-9x]+)/i', 'holder.js/' . $modifierSuffix, $filePath);
        }

        $_components = pathinfo($filePath);
        $_src        = '';

        if (isset($_components['dirname']) && $_components['dirname'] !== '.') {
            $_src .= $_components['dirname'] . '/';
        }

        if (empty($_components['filename'])) {
            return '';
        }

        $_src .= $_components['filename'] . '--' . $modifierSuffix;

        if (!empty($_components['extension'])) {
            $_src .= '.' . $_components['extension'];
        }

        return $_src;
    }
}
