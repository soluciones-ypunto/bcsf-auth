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
    <div class="media mt-4 align-items-center">
        <i class="fas fa-fw fa-laptop-code fa-2x mr-3"></i>
        <div class="media-body">
            <span class="text-muted small"><?= __('Aplicación') ?></span>
            <h2 class="h3 mt-n1">
                <?= \Cake\Core\Configure::read('Ypunto/Authentication.App.name', __('Application Name')) ?>

            </h2>
        </div>
    </div>
</div>
<div class="card-body px-4 px-xl-5 pb-4 text-right">
    <a class="btn btn-primary" href="<?= $this->Url->build([
        'controller' => 'Oauth2Check',
        'action' => 'index',
        '?' => ['redirect' => $this->getRequest()->getQuery('redirect')]
    ]) ?>">
        <i class="fas fa-sign-in-alt fa-fw"></i>
        <?= __('Iniciar Aplicación') ?>

    </a>
</div>
