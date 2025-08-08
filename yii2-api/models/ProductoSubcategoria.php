<?php

namespace app\models;

use Yii;

class ProductoSubcategoria extends \yii\db\ActiveRecord
{
    public static function tableName(){
        return 'producto_subcategoria';
    }

    public function rules(){
        return [
            [['producto_id', 'subcategoria_id', 'cantidad'], 'required'],
            [['producto_id', 'subcategoria_id', 'cantidad'], 'integer'],
            [['producto_id', 'subcategoria_id'], 'unique', 'targetAttribute' => ['producto_id', 'subcategoria_id']],
            [['producto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::class, 'targetAttribute' => ['producto_id' => 'id']],
            [['subcategoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subcategoria::class, 'targetAttribute' => ['subcategoria_id' => 'id']],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'producto_id' => 'Producto ID',
            'subcategoria_id' => 'Subcategoría ID',
            'cantidad' => 'Cantidad',
        ];
    }

    public function getProducto(){
        return $this->hasOne(Producto::class, ['id' => 'producto_id']);
    }

    public function getSubcategoria(){
        return $this->hasOne(Subcategoria::class, ['id' => 'subcategoria_id']);
    }
}
