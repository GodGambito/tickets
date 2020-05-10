<?php

namespace app\models;
use Yii;
use yii\base\model;

class SubidaForm extends model{
    public $nombre;
    public $categoria;    
    
    public function rules()
    {
        return [
            ['nombre', 'required', 'message' => 'Campo requerido'],
            ['categoria', 'default', 'value' => 2],          
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'nombre' => 'Origen de los Datos:',
            'categoria' => 'Proceso a Realizar',
           
        ];
    }
 
}