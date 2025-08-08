<?php

namespace app\models;

use Yii;

class Categoria extends \yii\db\ActiveRecord
{

    const ESTADO_ACTIVA = 'activa';
    const ESTADO_INACTIVA = 'inactiva';

    public static function tableName(){
        return 'categoria';
    }

    public static function primaryKey(){
        return ['id'];
    }

    public function rules(){
        return [
            [['estado'], 'default', 'value' => 'activa'],
            [['nombre'], 'required'],
            [['estado'], 'string'],
            [['nombre'], 'string', 'max' => 100],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }

    public function getSubcategorias(){
        return $this->hasMany(Subcategoria::class, ['categoria_id' => 'id']);
    }

    public static function optsEstado(){
        return [
            self::ESTADO_ACTIVA => 'activa',
            self::ESTADO_INACTIVA => 'inactiva',
        ];
    }

    public function displayEstado(){
        return self::optsEstado()[$this->estado];
    }

    public function isEstadoActiva(){
        return $this->estado === self::ESTADO_ACTIVA;
    }

    public function setEstadoToActiva(){
        $this->estado = self::ESTADO_ACTIVA;
    }

    public function isEstadoInactiva(){
        return $this->estado === self::ESTADO_INACTIVA;
    }

    public function setEstadoToInactiva(){
        $this->estado = self::ESTADO_INACTIVA;
    }

    public function inactivarConRelacion(){
        $this->setEstadoToInactiva();
        $this->save(false);

        foreach ($this->subcategorias as $subcategoria) {
            $subcategoria->setEstadoToInactiva();
            $subcategoria->save(false);

            foreach ($subcategoria->productos as $producto) {
                $producto->setEstadoToInactiva();
                $producto->save(false);
            }
        }
    }
}
