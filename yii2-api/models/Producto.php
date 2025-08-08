<?php

namespace app\models;

use Yii;

class Producto extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVA = 'activa';
    const ESTADO_INACTIVA = 'inactiva';

    public $subcategoria_id;
    public $cantidad;

    public function fields(){
        $fields = parent::fields();

        $fields['subcategorias_con_cantidad'] = function () {
            return array_map(function ($psc) {
                return [
                    'subcategoria_id' => $psc->subcategoria_id,
                    'subcategoria_nombre' => $psc->subcategoria->nombre ?? null,
                    'categoria_nombre' => $psc->subcategoria->categoria->nombre ?? null,
                    'cantidad' => $psc->cantidad,
                ];
            }, $this->productoSubcategorias);
        };

        return $fields;
    }

    public static function tableName(){
        return 'producto';
    }

    public function rules(){
        return [
            [['nombre'], 'required'],
            [['subcategoria_id', 'cantidad'], 'safe'],
            [['estado'], 'default', 'value' => self::ESTADO_ACTIVA],
            [['estado'], 'string'],
            [['nombre'], 'string', 'max' => 100],
            [['subcategoria_id', 'cantidad'], 'integer'],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
        ];
    }

    public function attributes(){
        return array_merge(parent::attributes(), ['subcategoria_id', 'cantidad']);
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
            'subcategoria_id' => 'Subcategoría',
            'cantidad' => 'Cantidad',
        ];
    }

    public static function find(){
        return parent::find();
    }

    public function getProductoSubcategorias(){
        return $this->hasMany(ProductoSubcategoria::class, ['producto_id' => 'id']);
    }

    public function getSubcategorias(){
        return $this->hasMany(Subcategoria::class, ['id' => 'subcategoria_id'])
            ->via('productoSubcategorias');
    }

    public static function optsEstado(){
        return [
            self::ESTADO_ACTIVA => 'activa',
            self::ESTADO_INACTIVA => 'inactiva',
        ];
    }

    public function setEstadoToInactiva(){
        $this->estado = self::ESTADO_INACTIVA;
    }

    public function isEstadoActiva(){
        return $this->estado === self::ESTADO_ACTIVA;
    }

    public function isEstadoInactiva(){
        return $this->estado === self::ESTADO_INACTIVA;
    }
}
