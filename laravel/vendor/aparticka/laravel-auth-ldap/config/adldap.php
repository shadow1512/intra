<?php

use adLDAP\adLDAP;

return [
    'account_suffix' => '@kodeks.net',
    'base_dn' => 'DC=programmers,DC=kodeks,DC=net',
    'domain_controllers' => [
        'programmers.kodeks.net',
        'work.kodeks.net',
    ],
    'admin_username' => null,
    'admin_password' => null,
    'real_primarygroup' => true,
    'use_ssl' => false,
    'use_tls' => false,
    'recursive_groups' => true,
    'ad_port' => adLDAP::ADLDAP_LDAP_PORT,
];
