<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */ 
$this->title = 'Tickets';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?= Html::jsFile('etc/main.js');
    
$directorio = opendir(Yii::$app->params['rutared']);
while ($archivo = readdir($directorio) ){
if($archivo!="Procesados" && $archivo!="RESTART" && $archivo!="tickets.log" && $archivo!="." && $archivo!=".."){
?>    <script>blinkTitle("Hay Tickets","*********",2000);</script> <?php } }?>

<div class="col-12 col-sm-12 ">
<h1><img src="etc/x-circle.svg" alt="" width="32" height="32" title="Roensa"> Proceso de Subida de Ticket al Alpha en <?php echo Yii::$app->params['rutaserver']; ?></h1>
</div>

<div class="row">
    <div class="col-md-5 center-block">    
        <?php
        $request = Yii::$app->request;
        $form = ActiveForm::begin([
            "method" => "post", 
            "enableClientValidation" => true,]);?>   
            <h2><?= $form->field($model, "nombre")-> dropDownList([Yii::$app->params['rutared'] => Yii::$app->params['rutared'], Yii::$app->params['rutalocal'] => Yii::$app->params['rutalocal']])?>
           </div>
</div>
<?php echo $form->field($model, 'categoria')->radioList([
    1 => 'Subir al Alpha', 
    2 => 'Listar Olas',
    3 => 'Eliminar 999'
]);?></h2>
<div class="row">
    <div class="col-md-4">
    <?= Html::submitButton("Procesar", ["class" => "btn btn-success"]);?>
    </div>
    
</div>      
        <?php $form->end(); ?>
        <span>&nbsp;</span>
    
<div class="row">
    <div class="col-md-8">
        <?= $mensaje;?>
    </div>

    <div class="col-md-4">Procesados Hoy <?php echo date('d').' de '.Yii::$app->params['mesactual']; ?>: <br>
        <?php 
        if (!file_exists(Yii::$app->params['rutalocal'].'diarios/'.Yii::$app->params['amd'].'.log')){
            fopen(Yii::$app->params['rutalocal'].'diarios/'.Yii::$app->params['amd'].'.log',"a");}
            include Yii::$app->params['rutalocal'].'diarios/'.Yii::$app->params['amd'].'.log';?>
    </div>
</div>



<!-- <div class="row">
    <div class="col-md-4">
    <?= Html::submitButton("Subir al Alpha", ["name" => "index" , "value" => "index" , "class" => "btn btn-success",
    'data' => [
        'method' => 'post'
    ]] );?>
    </div>
    <div class="col-md-4">
    <?= Html::submitButton("Listar Olas", ["name" => "listarolas" , "value" => "listarolas" , "class" => "btn btn-primary",
    'data' => [
        'method' => 'post'
    ]] );?>
    </div>
    <div class="col-md-4">
    <?=Html::submitButton("Eliminar 999",  ["name" => "eliminar999" , "value" => "eliminar999" , "class" => "btn btn-danger",
    'data' => [
        'method' => 'post'
    ]]);?>
    </div>        
</div>  -->    
   