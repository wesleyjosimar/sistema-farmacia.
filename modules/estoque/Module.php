<?php

namespace app\modules\estoque;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        // configurações personalizadas do módulo
        $this->params = [
            'adminEmail' => 'admin@example.com',
        ];
    }
} 