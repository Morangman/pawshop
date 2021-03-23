<?php

declare(strict_types = 1);

Route::delete('{step}', [
    'as' => '.delete-item',
    'uses' => 'StepController@deleteItem',
]);
