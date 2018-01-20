<?php
/**
 * Created by PhpStorm.
 * User: Rafy
 * Date: 20/01/2018
 * Time: 18.43
 */

namespace App\Support;


class Role
{
    const ADMIN = 'ADMIN';

    const KALAB = 'KALAB';

    const KASUBLAB = 'KASUBLAB';

    const ALL = [
        Role::ADMIN,
        Role::KALAB,
        Role::KASUBLAB
    ];
}