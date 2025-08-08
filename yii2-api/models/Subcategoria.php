<?php

namespace app\models;

use Yii;

class Subcategoria extends \yii\db\ActiveRecord
{

    const ESTADO_ACTIVA = 'activa';
    const ESTADO_INACTIVA = 'inactiva';

    public static function tableName(){
        return 'subcategoria';
    }

    public function fields()
    {
        $fields = parent::fields();

        // Elimina la relación completa si se incluyera
        unset($fields['categoria']);

        // Agrega el campo personalizado
        $fields['categoria_nombre'] = function () {
            return $this->categoria ? $this->categoria->nombre : null;
        };

        return $fields;
    }

    public function rules(){
        return [
            [['estado'], 'default', 'value' => 'activa'],
            [['nombre', 'categoria_id'], 'required'],
            [['categoria_id'], 'integer'],
            [['estado'], 'string'],
            [['nombre'], 'string', 'max' => 100],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'categoria_id' => 'Categoria ID',
            'estado' => 'Estado',
        ];
    }

    public function getCategoria(){
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    public function getProductos(){
        return $this->hasMany(Producto::class, ['id' => 'producto_id'])
                    ->via('productoSubcategorias');
    }

    public function getProductoSubcategorias(){
        return $this->hasMany(ProductoSubcategoria::class, ['subcategoria_id' => 'id']);
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

        foreach ($this->productos as $producto) {
            $producto->setEstadoToInactiva();
            $producto->save(false);
        }
    }
}
