<?php
// Create next/prev & first/last links for the current model.
$this->Paginator->meta(['first' => true, 'last' => true, 'block' => 'meta']);

// FIX: si perde i dati in post... (uhmnnn)
// $passedArgs = !empty($passedArgs) ? $passedArgs : true;
// if (!empty($passedArgs)) {
//     $this->Paginator->options(array('url' => $this->passedArgs));
// }
?>

<?php if ($this->Paginator->hasNext() || $this->Paginator->hasPrev()) : ?>
<nav class="container-fluid app-component-paginate">
    <div class="row">
        <div class="col-md-12">
            <ul class="pager">
                <?= $this->Paginator->prev(__('{0} Precedente', '<i class="fa fa-arrow-left"></i>'), ['escape' => false]) ?>
                <?= $this->Paginator->next(__('Prossima {0}', '<i class="fa fa-arrow-right"></i>'), ['escape' => false]) ?>
            </ul>
        </div>
    </div>
</nav>
<?php endif ?>
