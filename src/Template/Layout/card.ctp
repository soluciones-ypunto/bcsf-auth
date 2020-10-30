<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!doctype html>
<html class="h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $this->fetch('title') ?></title>

    <!-- Bootstrap core CSS -->
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('Ypunto/Admin.main') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>

<body class="h-100">
<main class="h-100" role="main">
    <div class="container-fluid h-100 bg-white">
        <div class="row h-100 align-items-center">
            <div class="col-md-7 col-lg-6 col-xl-4 mx-auto">
                <?= $this->Flash->render() ?>

                <div class="card">
                    <div class="brand-block text-center my-5" style="height: 70px">
                        <?= $this->Html->image('logo-bcsf-color.png', [
                            'class' => 'mr-4',
                            'style' => 'max-height: 100%; width: auto',
                        ]) ?>


                    </div>
                    <div class="card-body text-center px-4 px-lg-5">
                        <h1 class="mb-0 h2"><?= __('Iniciar Sesión') ?></h1>
                        <h2 class="h6 text-muted"><?= __('Sistema de Autenticación Único') ?></h2>
                    </div>

                    <?= $this->fetch('content') ?>

                </div>
                <div class="d-flex justify-content-between small py-4">
                    <span title="<?= __('Bolsa de Comercio de Santa Fe') ?>" class="text-muted"><?= __('BCSF') ?> &copy; <?= date('Y') ?></span>
                    <a title="<?= __('Soluciones yPunto') ?>" href="https://solucionesypunto.com" target="_blank" class="text-muted">
                        <?= $this->Html->image('ypunto-logo-min.png', ['style' => 'max-width: 140px;']) ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?= $this->Html->script([
    'Ypunto/Admin.jquery.min.js',
    'Ypunto/Admin.popper.min.js',
    'Ypunto/Admin.bootstrap.min.js',
]) ?>

<?= $this->fetch('script') ?>

</body>
</html>
