<?php
/**
 * Created by javier
 * Date: 6/06/19
 * Time: 11:22
 */
/**
 * @var \App\View\AppView $this
 */

$this->setLayout('card');
?>

<div class="card-body p-4 px-xl-5">
    <div class="border-top"></div>
    <div class="media mt-4">
        <i class="fas fa-fw fa-laptop-code fa-2x mr-3 mt-4"></i>
        <div class="media-body">
            <span class="text-muted small"><?= __('Aplicación') ?></span>
            <h2 class="h3 mt-n1"><?= __('Sistema de Laboratorios') ?> <?= \Cake\Core\Configure::read('debug') ? __('Test') : null ?></h2>
        </div>
    </div>
</div>
<div class="card-body px-4 px-xl-5 pb-4 text-right">
    <a class="btn btn-primary" href="<?= $this->Url->build([
        'controller' => 'Oauth2Check',
        'action' => 'index',
        '?' => ['redirect' => $this->request->getQuery('redirect')]
    ]) ?>">
        <i class="fas fa-sign-in-alt fa-fw"></i>
        <?= __('Iniciar Aplicación') ?>

    </a>
</div>
